<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $start = (News::all()->max('id') ?? 0) + 1;
        foreach (range($start, $start + 10) as $i) {
            $title = "News {$i}";
            News::create([
                'title' => $title,
                'slug' => Str::slug($title),
                'description' => 'Description news #'.$i,
                'content' => 'This **content** for news'.$i,
                'published_at' => now()->subDays($start + 10 - $i),
            ]);
        }
    }
}
