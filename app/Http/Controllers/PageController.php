<?php

namespace App\Http\Controllers;

use App\Models\Page;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $theme = config('theme.active');
        $page = Page::published()->where('slug', $slug)->firstOrFail();

        return view("{$theme}.pages.show", [
            'page' => $page,
        ]);
    }
}
