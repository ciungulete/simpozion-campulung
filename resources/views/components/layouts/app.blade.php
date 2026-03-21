<!DOCTYPE html>
<html lang="ro" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('simpozion.event_name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="min-h-screen bg-[#0c0e14] text-white antialiased">
    <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
        {{ $slot }}
    </div>

    @fluxScripts
</body>
</html>
