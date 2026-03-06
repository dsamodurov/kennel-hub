<?php

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

function createNews(array $overrides = []): News
{
    return News::create(array_merge([
        'title' => 'News title',
        'slug' => 'news-title-' . uniqid(),
        'description' => 'Short description',
        'content' => 'Body content',
        'published_at' => now()->subDay(),
    ], $overrides));
}

test('news index defaults to published filter', function () {
    $user = User::factory()->create();

    createNews(['title' => 'Published news', 'published_at' => now()->subDay()]);
    createNews(['title' => 'Scheduled news', 'published_at' => now()->addDay()]);
    createNews(['title' => 'No date news', 'published_at' => null]);
    $trashed = createNews(['title' => 'Trashed news', 'published_at' => now()->subDays(2)]);
    $trashed->delete();

    $response = $this->actingAs($user)->get(route('admin.news.index'));

    $response->assertInertia(fn (Assert $page) => $page
        ->component('admin/news/Index')
        ->where('filters.current', 'published')
        ->has('items.data', 1)
        ->where('items.data.0.title', 'Published news')
    );
});

test('news index can filter scheduled', function () {
    $user = User::factory()->create();

    createNews(['title' => 'Published news', 'published_at' => now()->subDay()]);
    createNews(['title' => 'Scheduled news', 'published_at' => now()->addDay()]);

    $response = $this->actingAs($user)->get(route('admin.news.index', ['filter' => 'scheduled']));

    $response->assertInertia(fn (Assert $page) => $page
        ->component('admin/news/Index')
        ->where('filters.current', 'scheduled')
        ->has('items.data', 1)
        ->where('items.data.0.title', 'Scheduled news')
    );
});

test('news index can filter without date', function () {
    $user = User::factory()->create();

    createNews(['title' => 'Published news', 'published_at' => now()->subDay()]);
    createNews(['title' => 'No date news', 'published_at' => null]);

    $response = $this->actingAs($user)->get(route('admin.news.index', ['filter' => 'no_date']));

    $response->assertInertia(fn (Assert $page) => $page
        ->component('admin/news/Index')
        ->where('filters.current', 'no_date')
        ->has('items.data', 1)
        ->where('items.data.0.title', 'No date news')
    );
});

test('news index can filter trashed', function () {
    $user = User::factory()->create();

    createNews(['title' => 'Published news', 'published_at' => now()->subDay()]);
    $trashed = createNews(['title' => 'Trashed news', 'published_at' => now()->subDays(2)]);
    $trashed->delete();

    $response = $this->actingAs($user)->get(route('admin.news.index', ['filter' => 'trashed']));

    $response->assertInertia(fn (Assert $page) => $page
        ->component('admin/news/Index')
        ->where('filters.current', 'trashed')
        ->has('items.data', 1)
        ->where('items.data.0.title', 'Trashed news')
    );
});

test('news can be restored', function () {
    $user = User::factory()->create();

    $news = createNews(['title' => 'Trashed news', 'published_at' => now()->subDay()]);
    $news->delete();

    $response = $this->actingAs($user)->post(route('admin.news.restore', $news->id));

    $response->assertRedirect(route('admin.news.index'));
    expect(News::withTrashed()->find($news->id)?->trashed())->toBeFalse();
});

test('news can be force deleted', function () {
    $user = User::factory()->create();

    $news = createNews(['title' => 'Trashed news', 'published_at' => now()->subDay()]);
    $news->delete();

    $response = $this->actingAs($user)->delete(route('admin.news.force-destroy', $news->id));

    $response->assertRedirect(route('admin.news.index'));
    expect(News::withTrashed()->find($news->id))->toBeNull();
});
