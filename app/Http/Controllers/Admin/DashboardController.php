<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\News;
use App\Models\Page;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $newsTotal = News::count();
        $newsPublished = News::published()->count();
        $newsScheduled = News::scheduled()->count();
        $newsNoDate = News::withoutDate()->count();
        $newsTrashed = News::onlyTrashed()->count();

        $pagesTotal = Page::count();
        $pagesPublished = Page::published()->count();
        $pagesDraft = Page::where('is_published', false)->count();

        $mediaTotal = Media::count();
        $mediaUsed = Media::has('usages')->count();
        $mediaUnused = Media::doesntHave('usages')->count();

        $recentNews = News::withTrashed()
            ->latest('updated_at')
            ->take(5)
            ->get(['id', 'title', 'slug', 'published_at', 'updated_at', 'deleted_at'])
            ->map(fn (News $news) => [
                'id' => $news->id,
                'title' => $news->title,
                'slug' => $news->slug,
                'published_at' => $news->published_at?->format('Y-m-d H:i'),
                'updated_at' => $news->updated_at?->format('Y-m-d H:i'),
                'is_trashed' => $news->trashed(),
            ])
            ->all();

        $recentPages = Page::query()
            ->latest('updated_at')
            ->take(5)
            ->get(['id', 'title', 'slug', 'is_published', 'updated_at'])
            ->map(fn (Page $page) => [
                'id' => $page->id,
                'title' => $page->title,
                'slug' => $page->slug,
                'is_published' => (bool) $page->is_published,
                'updated_at' => $page->updated_at?->format('Y-m-d H:i'),
            ])
            ->all();

        $recentMedia = Media::withCount('usages')
            ->latest('created_at')
            ->take(8)
            ->get(['id', 'path', 'original_name', 'created_at'])
            ->map(fn (Media $media) => [
                'id' => $media->id,
                'url' => $media->url,
                'original_name' => $media->original_name,
                'usage_count' => $media->usages_count ?? 0,
                'created_at' => $media->created_at?->format('Y-m-d H:i'),
            ])
            ->all();

        return Inertia::render('Dashboard', [
            'stats' => [
                'news' => [
                    'total' => $newsTotal,
                    'published' => $newsPublished,
                    'scheduled' => $newsScheduled,
                    'no_date' => $newsNoDate,
                    'trashed' => $newsTrashed,
                ],
                'pages' => [
                    'total' => $pagesTotal,
                    'published' => $pagesPublished,
                    'draft' => $pagesDraft,
                ],
                'media' => [
                    'total' => $mediaTotal,
                    'used' => $mediaUsed,
                    'unused' => $mediaUnused,
                ],
            ],
            'recentNews' => $recentNews,
            'recentPages' => $recentPages,
            'recentMedia' => $recentMedia,
        ]);
    }
}
