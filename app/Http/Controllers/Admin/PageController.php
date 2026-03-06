<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Media;
use App\Models\MediaUsage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function index(Request $request): Response
    {
        $items = Page::query()
            ->latest('updated_at')
            ->select('id', 'title', 'slug', 'is_published', 'updated_at')
            ->paginate(20)
            ->withQueryString()
            ->through(fn (Page $page) => [
                'id' => $page->id,
                'title' => $page->title,
                'slug' => $page->slug,
                'is_published' => $page->is_published,
                'updated_at' => $page->updated_at?->format('Y-m-d H:i'),
            ]);

        return Inertia::render('admin/pages/Index', [
            'items' => $items,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/pages/Edit', [
            'item' => null,
            'selected_media' => [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $base = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'is_published' => ['boolean'],
        ]);

        $slug = $request->input('slug');
        if (! $slug) {
            $slug = Str::slug($base['title']);
        }

        $this->validateSlug($slug);
        Validator::make(['slug' => $slug], [
            'slug' => ['required', 'string', 'max:255', 'unique:pages,slug'],
        ])->validate();

        $page = Page::create([
            ...$base,
            'slug' => $slug,
        ]);

        $this->syncMediaUsages($page, $base['content'] ?? null);

        return redirect()->route('admin.pages.index')->with('success', 'Page created');
    }

    public function edit(Page $page): Response
    {
        $selectedMedia = $this->getSelectedMedia($page);

        return Inertia::render('admin/pages/Edit', [
            'item' => [
                'id' => $page->id,
                'title' => $page->title,
                'slug' => $page->slug,
                'content' => $page->content,
                'is_published' => $page->is_published,
            ],
            'selected_media' => $selectedMedia,
        ]);
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $base = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'is_published' => ['boolean'],
        ]);

        $slug = $request->input('slug');
        if (! $slug) {
            $slug = Str::slug($base['title']);
        }

        $this->validateSlug($slug);
        Validator::make(['slug' => $slug], [
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pages', 'slug')->ignore($page->id),
            ],
        ])->validate();

        $page->update([
            ...$base,
            'slug' => $slug,
        ]);

        $this->syncMediaUsages($page, $base['content'] ?? null);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated');
    }

    public function destroy(Page $page): RedirectResponse
    {
        MediaUsage::where('model_type', Page::class)
            ->where('model_id', $page->id)
            ->delete();

        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Page deleted');
    }

    private function validateSlug(string $slug): void
    {
        $reserved = array_map('strtolower', config('pages.reserved_slugs', []));
        $normalized = Str::lower($slug);

        if (in_array($normalized, $reserved, true)) {
            Validator::make([], [])->after(function ($validator) {
                $validator->errors()->add('slug', 'Этот slug зарезервирован системой.');
            })->validate();
        }
    }

    private function syncMediaUsages(Page $page, ?string $content): void
    {
        $mediaIds = $this->extractMediaIds($content);

        $query = MediaUsage::where('model_type', Page::class)
            ->where('model_id', $page->id)
            ->where('field', 'content');

        if (empty($mediaIds)) {
            $query->delete();
            return;
        }

        $existing = $query->pluck('media_id')->all();
        $toDelete = array_diff($existing, $mediaIds);
        $toAdd = array_diff($mediaIds, $existing);

        if (!empty($toDelete)) {
            MediaUsage::where('model_type', Page::class)
                ->where('model_id', $page->id)
                ->where('field', 'content')
                ->whereIn('media_id', $toDelete)
                ->delete();
        }

        foreach ($toAdd as $mediaId) {
            MediaUsage::create([
                'media_id' => $mediaId,
                'model_type' => Page::class,
                'model_id' => $page->id,
                'field' => 'content',
            ]);
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

    private function getSelectedMedia(Page $page): array
    {
        $mediaIds = MediaUsage::where('model_type', Page::class)
            ->where('model_id', $page->id)
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
