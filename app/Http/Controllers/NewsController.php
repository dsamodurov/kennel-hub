<?php

namespace App\Http\Controllers;

use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $theme = config('theme.active');
        $items = News::published()
            ->with('coverMedia')
            ->latest('published_at')
            ->select('id','title','slug','description', 'cover_media_id', 'published_at')
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($item) => $item);

        return view("{$theme}.news.index", [
            'items' => $items,
        ]);
    }

    public function show(string $slug)
    {
        $theme = config('theme.active');
        $item = News::published()
            ->with('coverMedia')
            ->where('slug', $slug)
            ->firstOrFail();

        return view("{$theme}.news.show", [
            'item' => $item,
        ]);
    }
}
