@extends('default.layout')

@section('title', $page->title)

@section('content')
    <div class="mb-6">
        <a href="{{ route('home') }}" class="text-sm text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-100">← На главную</a>
    </div>

    <article class="rounded-2xl bg-white p-6 shadow-sm dark:bg-slate-900">
        <h1 class="text-3xl font-semibold">{{ $page->title }}</h1>
        <div class="prose mt-6 max-w-none dark:prose-invert">
            {!! $page->contentHtml !!}
        </div>
    </article>
@endsection
