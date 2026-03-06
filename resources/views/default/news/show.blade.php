@extends('default.layout')

@section('title', $item->title)

@section('content')
    <div class="mb-6">
        <a href="{{ route('news.index') }}" class="text-sm text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-100">← Назад к новостям</a>
    </div>

    <article class="rounded-2xl bg-white p-6 shadow-sm dark:bg-slate-900">
        @if ($item->coverUrl)
            <img src="{{ $item->coverUrl }}" alt="{{ $item->title }}" class="mb-6 w-full rounded-xl object-cover">
        @endif
        <h1 class="text-3xl font-semibold">{{ $item->title }}</h1>
        @if ($item->published_at)
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                Опубликовано:
                <time data-local-datetime datetime="{{ $item->published_at->toIso8601String() }}">
                    {{ $item->published_at->format('d.m.Y H:i') }}
                </time>
            </p>
        @endif
        <div class="prose mt-6 max-w-none dark:prose-invert">
            {!! $item->contentHtml !!}
        </div>
    </article>
@endsection
