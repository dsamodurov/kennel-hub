<header class="border-b bg-white dark:border-slate-800 dark:bg-slate-900">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
        <a href="{{ route('home') }}" class="text-lg font-semibold">
            {{ config('app.name', 'Kennel Hub') }}
        </a>
        @include('default.partials.nav')
    </div>
</header>
