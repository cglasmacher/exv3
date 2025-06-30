<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>

    {{-- Inertia Head --}}
    @inertiaHead

    {{-- Vite Assets --}}
    @viteReactRefresh
    @vite([
        'resources/js/bootstrap.js',
        'resources/js/app.tsx',
        'resources/css/app.css'
    ])
</head>
<body>
    {{-- Inertia Root --}}
    @inertia
</body>
</html>
