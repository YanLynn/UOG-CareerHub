<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />


        @vite(['resources/js/app.js', 'resources/css/app.css'])
    </head>

    <body class="bg-white text-gray-800 dark:bg-gray-900 dark:text-gray-300">
        <div id="app">
            <App />
        </div>
    </body>

</html>
