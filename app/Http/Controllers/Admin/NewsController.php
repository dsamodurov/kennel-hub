<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\MediaUsage;
use App\Models\News;
use App\Support\StoresImagesAsWebp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
{
    public function index(Request $request): Response
    {
        $perPage = $request->integer('per_page', 20);
        $filter = $request->input('filter', 'published');

        $query = News::query();

        // Применяем фильтры
        switch ($filter) {
            case 'all':
                $query->withTrashed();
                break;
            case 'scheduled':
                $query->scheduled();
                break;
            case 'no_date':
                $query->withoutDate();
                break;
            case 'trashed':
                $query->onlyTrashed();
                break;
            default:
                $query->published();
                break;
        }

        $items = $query
            ->latest('published_at')
            ->select('id','title','slug','published_at','deleted_at')
            ->paginate($perPage)
            ->withQueryString();

        $props = [
            'items' => $items
                ->through(fn ($item) => [
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                    'published_at' => $item->published_at
                        ? Carbon::parse($item->published_at)->utc()->toIso8601String()
                        : null,
                    'published_at_human' => $item->published_at
                        ? Carbon::parse($item->published_at)->locale(app()->getLocale())->diffForHumans(now())
                        : null,
                    'is_trashed' => $item->deleted_at !== null,
                ]),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
            ],
            'filters' => [
                'current' => $filter,
            ],
        ];

        return Inertia::render('admin/news/Index', $props);
    }

    public function create(): Response
    {
        return Inertia::render('admin/news/Edit', [
            'item' => null,
            'selected_media' => [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255','unique:news,slug'],
            'description' => ['nullable','string'],
            'content' => ['nullable','string'],
            'published_at' => ['nullable','date'],
            'cover' => ['nullable','image','max:5120'],
            'cover_media_id' => ['nullable', 'integer', 'exists:media,id'],
        ]);

        $coverMediaId = $validated['cover_media_id'] ?? null;
        unset($validated['cover'], $validated['cover_media_id']);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if (!empty($validated['published_at'])) {
            $validated['published_at'] = Carbon::parse($validated['published_at'])->utc();
        }

        if ($request->hasFile('cover')) {
            $media = $this->storeCoverAsMedia($request->file('cover'));
            $coverMediaId = $media->id;
        } elseif ($coverMediaId) {
            $media = Media::find($coverMediaId);
            $coverMediaId = $media?->id;
        }

        if ($coverMediaId) {
            $validated['cover_media_id'] = $coverMediaId;
        }

        $news = News::create($validated);

        $this->syncMediaUsages(
            $news,
            $validated['content'] ?? null,
            $validated['description'] ?? null,
            $coverMediaId
        );

        return redirect()->route('admin.news.index')->with('success', 'News created');
    }

    public function edit(News $news): Response
    {
        $selectedMedia = $this->getSelectedMedia($news);

        return Inertia::render('admin/news/Edit', [
            'item' => [
                'id' => $news->id,
                'title' => $news->title,
                'slug' => $news->slug,
                'description' => $news->description,
                'content' => $news->content,
                'published_at' => $news->published_at
                    ? Carbon::parse($news->published_at)->utc()->toIso8601String()
                    : null,
                'cover_url' => $news->coverUrl,
                'cover_media_id' => $news->cover_media_id,
            ],
            'selected_media' => $selectedMedia,
        ]);
    }

    public function update(Request $request, News $news): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required','string','max:255'],
            'slug' => ['required','string','max:255', Rule::unique('news','slug')->ignore($news->id)],
            'description' => ['nullable','string'],
            'content' => ['nullable','string'],
            'published_at' => ['nullable','date'],
            'cover' => ['nullable','image','max:5120'],
            'remove_cover' => ['nullable','boolean'],
            'cover_media_id' => ['nullable', 'integer', 'exists:media,id'],
        ]);

        $validated['published_at'] = $validated['published_at']
            ? Carbon::parse($validated['published_at'])->utc()
            : null;

        $coverMediaId = $news->cover_media_id;
        if (array_key_exists('cover_media_id', $validated)) {
            $coverMediaId = $validated['cover_media_id'];
        }
        $removeCover = $request->boolean('remove_cover');
        unset($validated['cover'], $validated['remove_cover'], $validated['cover_media_id']);

        if ($removeCover) {
            $validated['cover_media_id'] = null;
            $coverMediaId = null;
        }

        if ($request->hasFile('cover')) {
            $media = $this->storeCoverAsMedia($request->file('cover'));
            $coverMediaId = $media->id;
        } elseif ($coverMediaId) {
            $media = Media::find($coverMediaId);
            $coverMediaId = $media?->id;
        }

        if ($coverMediaId !== null) {
            $validated['cover_media_id'] = $coverMediaId;
        }

        $news->update($validated);

        $this->syncMediaUsages(
            $news,
            $validated['content'] ?? $news->content,
            $validated['description'] ?? $news->description,
            $coverMediaId
        );

        return redirect()->route('admin.news.index')->with('success', 'News updated');
    }

    public function destroy(News $news): RedirectResponse
    {
        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'News trashed');
    }

    public function restore(int $id): RedirectResponse
    {
        $news = News::onlyTrashed()->findOrFail($id);
        $news->restore();

        return redirect()->route('admin.news.index')->with('success', 'News restored');
    }

    public function forceDestroy(int $id): RedirectResponse
    {
        $news = News::onlyTrashed()->findOrFail($id);

        MediaUsage::where('model_type', News::class)
            ->where('model_id', $news->id)
            ->delete();

        $news->forceDelete();

        return redirect()->route('admin.news.index')->with('success', 'News permanently deleted');
    }

    private function storeCoverAsMedia($file): Media
    {
        $stored = StoresImagesAsWebp::store($file, 'news_covers');

        return Media::create([
            'path' => $stored['path'],
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $stored['mime_type'],
            'size' => $stored['size'],
            'width' => $stored['width'],
            'height' => $stored['height'],
        ]);
    }

    private function syncMediaUsages(News $news, ?string $content, ?string $description, ?int $coverMediaId): void
    {
        $fieldMap = [
            'content' => $content,
            'description' => $description,
        ];

        foreach ($fieldMap as $field => $markdown) {
            $mediaIds = $this->extractMediaIds($markdown);

            $query = MediaUsage::where('model_type', News::class)
                ->where('model_id', $news->id)
                ->where('field', $field);

            if (empty($mediaIds)) {
                $query->delete();
                continue;
            }

            $existing = $query->pluck('media_id')->all();
            $toDelete = array_diff($existing, $mediaIds);
            $toAdd = array_diff($mediaIds, $existing);

            if (!empty($toDelete)) {
                MediaUsage::where('model_type', News::class)
                    ->where('model_id', $news->id)
                    ->where('field', $field)
                    ->whereIn('media_id', $toDelete)
                    ->delete();
            }

            foreach ($toAdd as $mediaId) {
                MediaUsage::create([
                    'media_id' => $mediaId,
                    'model_type' => News::class,
                    'model_id' => $news->id,
                    'field' => $field,
                ]);
            }
        }

        $coverQuery = MediaUsage::where('model_type', News::class)
            ->where('model_id', $news->id)
            ->where('field', 'cover');

        if ($coverMediaId) {
            $exists = $coverQuery->where('media_id', $coverMediaId)->exists();
            if (! $exists) {
                MediaUsage::create([
                    'media_id' => $coverMediaId,
                    'model_type' => News::class,
                    'model_id' => $news->id,
                    'field' => 'cover',
                ]);
            }
        } else {
            $coverQuery->delete();
        }
    }

    private function extractMediaIds(?string $markdown): array
    {
        if (! $markdown) {
            return [];
        }

        $urls = [];
        if (preg_match_all('/!\\[[^\\]]*\\]\\(([^)]+)\\)/', $markdown, $matches)) {
            $urls = array_merge($urls, $matches[1]);
        }
        if (preg_match_all('/<img[^>]+src=[\"\\\']([^\"\\\']+)[\"\\\']/i', $markdown, $matches)) {
            $urls = array_merge($urls, $matches[1]);
        }

        $paths = [];
        foreach ($urls as $url) {
            $url = trim($url);
            if ($url === '') {
                continue;
            }

            $url = trim($url, "<>\"");
            $parts = preg_split('/\\s+/', $url);
            $url = $parts[0] ?? '';

            $path = parse_url($url, PHP_URL_PATH) ?: '';
            $storagePos = strpos($path, '/storage/');
            if ($storagePos === false) {
                continue;
            }
            $relative = substr($path, $storagePos + 9);
            if ($relative !== '') {
                $paths[] = $relative;
            }
        }

        $paths = array_values(array_unique($paths));
        if (empty($paths)) {
            return [];
        }

        return Media::whereIn('path', $paths)->pluck('id')->all();
    }

    private function getSelectedMedia(News $news): array
    {
        $mediaIds = MediaUsage::where('model_type', News::class)
            ->where('model_id', $news->id)
            ->pluck('media_id')
            ->all();

        if (empty($mediaIds)) {
            return [];
        }

        return Media::whereIn('id', $mediaIds)
            ->get(['id', 'path', 'original_name', 'alt_text'])
            ->map(fn (Media $media) => [
                'id' => $media->id,
                'url' => $media->url,
                'original_name' => $media->original_name,
                'alt_text' => $media->alt_text,
            ])
            ->all();
    }
}
