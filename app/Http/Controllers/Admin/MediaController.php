<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\MediaUsage;
use App\Models\News;
use App\Support\StoresImagesAsWebp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class MediaController extends Controller
{
    public function index(Request $request): Response
    {
        $perPage = $request->integer('per_page', 24);
        $search = trim((string) $request->input('search', ''));
        $inUse = $request->input('in_use');

        $items = $this->query($search)
            ->when($inUse === '1', fn ($query) => $query->has('usages'))
            ->when($inUse === '0', fn ($query) => $query->doesntHave('usages'))
            ->withCount('usages')
            ->with(['usages.model'])
            ->latest()
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn (Media $media) => $this->transform($media));

        return Inertia::render('admin/media/Index', [
            'items' => $items,
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
            ],
            'filters' => [
                'search' => $search,
                'in_use' => $inUse,
            ],
        ]);
    }

    public function library(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 24);
        $search = trim((string) $request->input('search', ''));
        $inUse = $request->input('in_use');

        $items = $this->query($search)
            ->when($inUse === '1', fn ($query) => $query->has('usages'))
            ->when($inUse === '0', fn ($query) => $query->doesntHave('usages'))
            ->withCount('usages')
            ->with(['usages.model'])
            ->latest()
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn (Media $media) => $this->transform($media));

        return response()->json([
            'items' => $items,
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
            ],
            'filters' => [
                'search' => $search,
                'in_use' => $inUse,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'file' => ['required', 'image', 'max:8192'],
            'alt' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $validated['file'];
        $stored = StoresImagesAsWebp::store($file, 'news_gallery');

        $media = Media::create([
            'path' => $stored['path'],
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $stored['mime_type'],
            'size' => $stored['size'],
            'width' => $stored['width'],
            'height' => $stored['height'],
            'alt_text' => $validated['alt'] ?? null,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'item' => $this->transform($media),
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Image uploaded');
    }

    public function upload(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'image', 'max:8192'],
        ]);

        $file = $validated['file'];
        $stored = StoresImagesAsWebp::store($file, 'news_gallery');

        $media = Media::create([
            'path' => $stored['path'],
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $stored['mime_type'],
            'size' => $stored['size'],
            'width' => $stored['width'],
            'height' => $stored['height'],
        ]);

        return response()->json([
            'code' => 0,
            'msg' => '',
            'data' => [
                'errFiles' => [],
                'succMap' => [
                    $media->original_name => $media->url,
                ],
            ],
        ]);
    }

    public function update(Request $request, Media $media): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'alt' => ['nullable', 'string', 'max:255'],
        ]);

        $media->update([
            'alt_text' => $validated['alt'] ?? null,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'item' => $this->transform($media->fresh(['usages'])),
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Alt updated');
    }

    public function destroy(Media $media): RedirectResponse
    {
        if ($media->usages()->exists()) {
            return redirect()
                ->back()
                ->withErrors([
                    'media' => 'Изображение используется и не может быть удалено.',
                ]);
        }

        Storage::disk('public')->delete($media->path);
        $media->delete();

        return redirect()
            ->back()
            ->with('success', 'Image deleted');
    }

    private function query(string $search)
    {
        return Media::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('original_name', 'like', "%{$search}%")
                        ->orWhere('alt_text', 'like', "%{$search}%");
                });
            });
    }

    private function transform(Media $media): array
    {
        $usages = $media->usages->take(5)->map(function (MediaUsage $usage) {
            $model = $usage->model;
            $label = class_basename($usage->model_type) . ' #' . $usage->model_id;
            $meta = null;

            if ($model instanceof News) {
                $label = 'Новость: ' . $model->title;
                $meta = [
                    'slug' => $model->slug,
                ];
            }

            return [
                'id' => $usage->id,
                'model_type' => $usage->model_type,
                'model_id' => $usage->model_id,
                'field' => $usage->field,
                'label' => $label,
                'meta' => $meta,
            ];
        });

        return [
            'id' => $media->id,
            'url' => $media->url,
            'original_name' => $media->original_name,
            'mime_type' => $media->mime_type,
            'size' => $media->size,
            'width' => $media->width,
            'height' => $media->height,
            'alt_text' => $media->alt_text,
            'usage_count' => $media->usages_count ?? 0,
            'usages' => $usages,
            'created_at' => $media->created_at?->format('Y-m-d H:i'),
        ];
    }
}
