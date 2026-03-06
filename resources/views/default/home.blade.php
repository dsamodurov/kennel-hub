@extends('default.layout')

@section('title', 'Питомник ' . config('app.name'))

@section('content')
    <section class="grid gap-8 rounded-2xl bg-white p-8 shadow-sm dark:bg-slate-900 md:grid-cols-2">
        <div class="space-y-4">
            <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Питомник</p>
            <h1 class="text-3xl font-semibold md:text-4xl">
                Добро пожаловать в питомник {{ config('app.name', 'Kennel Hub') }}
            </h1>
            <p class="text-slate-600 dark:text-slate-300">
                Мы выращиваем здоровых и дружелюбных собак, помогаем подобрать щенка и
                поддерживаем владельцев на всех этапах.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('news.index') }}" class="rounded-md bg-slate-900 px-4 py-2 text-sm text-white dark:bg-slate-100 dark:text-slate-900">
                    Читать новости
                </a>
                <a href="#contact" class="rounded-md border border-slate-300 px-4 py-2 text-sm text-slate-700 dark:border-slate-700 dark:text-slate-200">
                    Связаться
                </a>
            </div>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800 dark:bg-slate-950">
                <div class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800">
                    <svg viewBox="0 0 24 24" class="h-5 w-5 text-slate-700 dark:text-slate-200" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 4.5c1.6 0 2.7 1.4 2.7 2.9 0 1.6-1.1 2.9-2.7 2.9s-2.7-1.3-2.7-2.9C9.3 5.9 10.4 4.5 12 4.5Z"/>
                        <path d="M6 13.5c1.3 0 2.3 1.1 2.3 2.4S7.3 18.3 6 18.3 3.7 17.2 3.7 15.9 4.7 13.5 6 13.5Z"/>
                        <path d="M18 13.5c1.3 0 2.3 1.1 2.3 2.4S19.3 18.3 18 18.3s-2.3-1.1-2.3-2.4S16.7 13.5 18 13.5Z"/>
                        <path d="M8.5 17.5c0 1.7 1.5 3 3.5 3s3.5-1.3 3.5-3c0-1.8-1.5-3-3.5-3s-3.5 1.2-3.5 3Z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold">Ответственный подход</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Следим за здоровьем, социализацией и качеством условий содержания.
                </p>
            </div>
            <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800 dark:bg-slate-950">
                <div class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800">
                    <svg viewBox="0 0 24 24" class="h-5 w-5 text-slate-700 dark:text-slate-200" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 6.5c2.8-2.3 6.5-.8 6.5 2.5 0 3.5-3.3 6.1-6.5 8.5-3.2-2.4-6.5-5-6.5-8.5 0-3.3 3.7-4.8 6.5-2.5Z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold">Поддержка владельцев</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Консультации по уходу, питанию и воспитанию в любое время.
                </p>
            </div>
            <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800 dark:bg-slate-950">
                <div class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800">
                    <svg viewBox="0 0 24 24" class="h-5 w-5 text-slate-700 dark:text-slate-200" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M6 7h12M6 12h12M6 17h12"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold">Прозрачные документы</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Договор, ветпаспорт, рекомендации по адаптации в новом доме.
                </p>
            </div>
            <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800 dark:bg-slate-950">
                <div class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800">
                    <svg viewBox="0 0 24 24" class="h-5 w-5 text-slate-700 dark:text-slate-200" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M6.5 18.5c2.3 1.7 8.7 1.7 11 0 1.6-1.2 1.6-3.1 0-4.3-2.3-1.7-8.7-1.7-11 0-1.6 1.2-1.6 3.1 0 4.3Z"/>
                        <path d="M8 10.5c0 1.6 1.8 3 4 3s4-1.4 4-3-1.8-3-4-3-4 1.4-4 3Z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold">Породные линии</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Подбираем пары с учетом темперамента, экстерьера и здоровья.
                </p>
            </div>
        </div>
    </section>

    <section class="mt-10 grid gap-6 md:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-base font-semibold">Актуальные пометы</h3>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                Следите за новостями о планах вязок и доступных щенках.
            </p>
            <a href="{{ route('news.index') }}" class="mt-4 inline-flex text-sm font-medium text-slate-900 dark:text-slate-100">
                Смотреть новости →
            </a>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-base font-semibold">Сервис и уход</h3>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                Рекомендации по кормлению, профилактике и тренировкам.
            </p>
        </div>
        <div id="contact" class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-base font-semibold">Контакты питомника</h3>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                Телефон: +7 (000) 000-00-00<br>
                Почта: info@kennel-hub.test
            </p>
        </div>
    </section>

    @if (($latestNews ?? collect())->isNotEmpty())
        <section class="mt-10">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold">Последние новости</h2>
                <a href="{{ route('news.index') }}" class="text-sm text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-100">
                    Все новости →
                </a>
            </div>
            <div class="grid auto-rows-fr gap-4 md:grid-cols-3">
                @foreach ($latestNews as $item)
                    <article class="flex h-full flex-col rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900">
                        <h3 class="text-base font-semibold">
                            <a href="{{ route('news.show', $item->slug) }}" class="hover:text-slate-700 dark:hover:text-slate-200">
                                {{ $item->title }}
                            </a>
                        </h3>
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
        </section>
    @endif
@endsection
