<!DOCTYPE html>
<html lang="ru" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>STADIUM - @yield('title', 'Мультиформатная летняя площадка')</title>
    
    <!-- Стили -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Дополнительные стили -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="h-full flex flex-col">
    <div id="app" class="flex flex-col min-h-screen">
        @yield('content')
    </div>
</body>
</html>