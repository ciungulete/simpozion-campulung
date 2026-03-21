<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        $locale = app()->getLocale();
        $ogTitle = config("simpozion.event_name.{$locale}") . ' — ' . config("simpozion.event_title.{$locale}");
        $ogDescription = config("simpozion.event_subtitle.{$locale}") . ' | ' . config("simpozion.event_edition.{$locale}") . ' | ' . config('simpozion.event_location') . ' | ' . config("simpozion.event_date.{$locale}");
        $ogUrl = url()->current();
    @endphp

    <title>{{ $title ?? $ogTitle }}</title>

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    <meta property="og:url" content="{{ $ogUrl }}">
    <meta property="og:locale" content="{{ $locale === 'ro' ? 'ro_RO' : 'en_US' }}">
    <meta property="og:site_name" content="{{ config("simpozion.event_name.{$locale}") }}">
    @if(file_exists(public_path('og-image.jpg')))
        <meta property="og:image" content="{{ asset('og-image.jpg') }}">
    @endif

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ogTitle }}">
    <meta name="twitter:description" content="{{ $ogDescription }}">
    @if(file_exists(public_path('og-image.jpg')))
        <meta name="twitter:image" content="{{ asset('og-image.jpg') }}">
    @endif

    {{-- General meta --}}
    <meta name="description" content="{{ $ogDescription }}">
    <meta name="theme-color" content="#0c0e14">

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
