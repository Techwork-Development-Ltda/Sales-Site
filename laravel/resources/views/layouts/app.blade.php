<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'MyLearningThings')</title>

    </head>

    <body>
        <div>

            {{-- Header fixo --}}
            <header class="flex">
                
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