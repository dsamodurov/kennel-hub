<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', config('app.name'))</title>
        <script>
            (function () {
                const stored = localStorage.getItem('appearance');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const theme = stored || (prefersDark ? 'dark' : 'light');
                document.documentElement.classList.toggle('dark', theme === 'dark');
            })();
        </script>
        @php
            $themeName = config('theme.active');
            $themePath = resource_path("themes/{$themeName}/theme.json");
            $themeConfig = file_exists($themePath) ? json_decode(file_get_contents($themePath), true) : null;
            $lightVars = $themeConfig['light'] ?? [];
            $darkVars = $themeConfig['dark'] ?? [];
        @endphp
        @if (!empty($lightVars) || !empty($darkVars))
            <style>
                :root {
                    @foreach ($lightVars as $key => $value)
                        --{{ $key }}: {{ $value }};
                    @endforeach
                }
                .dark {
                    @foreach ($darkVars as $key => $value)
                        --{{ $key }}: {{ $value }};
                    @endforeach
                }
            </style>
        @endif
        @vite('resources/css/app.css')
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
        @include('default.partials.header')

        <main class="mx-auto w-full max-w-6xl px-6 py-10">
            @yield('content')
        </main>

        @include('default.partials.footer')
        <script>
            (function () {
                const pad = (value) => String(value).padStart(2, '0');

                const formatDate = (date) => {
                    const day = pad(date.getDate());
                    const month = pad(date.getMonth() + 1);
                    const year = date.getFullYear();
                    const hours = pad(date.getHours());
                    const minutes = pad(date.getMinutes());

                    return `${day}.${month}.${year} ${hours}:${minutes}`;
                };

                document.querySelectorAll('[data-local-datetime]').forEach((element) => {
                    const datetime = element.getAttribute('datetime');
                    if (!datetime) return;

                    const date = new Date(datetime);
                    if (Number.isNaN(date.getTime())) return;

                    element.textContent = formatDate(date);
                });
            })();
        </script>
    </body>
</html>
