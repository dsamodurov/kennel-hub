<nav class="flex items-center gap-4 text-sm">
    <a href="{{ route('home') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Главная</a>
    <a href="{{ route('news.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Новости</a>
    @foreach ($publicPages ?? [] as $page)
        <a href="{{ route('pages.show', $page->slug) }}" class="hover:text-slate-700 dark:hover:text-slate-200">
            {{ $page->title }}
        </a>
    @endforeach
</nav>
