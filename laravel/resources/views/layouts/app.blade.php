<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Laravel Skeleton')</title>

    </head>

    <body>
        <div>

            {{-- Header fixo --}}
            <header>

            </header>

            {{-- Conteúdo específico de cada página --}}
            <main>
                @yield('content')
            </main>

            {{-- Footer fixo --}}
            <footer>

            </footer>
        </div>
  </body>
</html>