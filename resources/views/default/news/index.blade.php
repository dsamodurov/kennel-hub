@extends('default.layout')

@section('title', 'Новости')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">Новости питомника</h1>
        <p class="text-sm text-slate-600 dark:text-slate-300">
            Последние события, планы и обновления.
        </p>
    </div>

    <div class="grid auto-rows-fr gap-6 md:grid-cols-2">
        @foreach ($items as $item)
            <article class="flex h-full flex-col rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900">
                @if ($item->coverUrl)
                    <img src="{{ $item->coverUrl }}" alt="{{ $item->title }}" class="mb-4 h-48 w-full rounded-lg object-cover">
                @else
                    <div class="mb-4 flex h-48 items-center justify-center rounded-lg bg-slate-100 text-slate-400 dark:bg-slate-800">
                        <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M4 6.5h16M4 12h16M4 17.5h16"/>
                        </svg>
                    </div>
                @endif
                <h2 class="text-lg font-semibold">
                    <a href="{{ route('news.show', $item->slug) }}" class="hover:text-slate-700 dark:hover:text-slate-200">
                        {{ $item->title }}
                    </a>
                </h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                    {!! $item->descriptionHtml !!}
                </p>
                @if ($item->published_at)
                    <p class="mt-4 mt-auto text-xs text-slate-500 dark:text-slate-400">
                        Опубликовано:
                        <time data-local-datetime datetime="{{ $item->published_at->toIso8601String() }}">
                            {{ $item->published_at->format('d.m.Y H:i') }}
                        </time>
                    </p>
                @endif
            </article>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $items->links() }}
    </div>
@endsection
