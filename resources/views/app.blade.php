<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ================= BASIC META ================= -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    <title inertia>
        {{ $siteSettings['site_name'] ?? config('app.name') }}
    </title>

    <meta name="description"
          content="{{ $siteSettings['site_tagline'] ?? 'A Reunion of Dynamic Leaders' }}">

    <meta name="author"
          content="{{ $siteSettings['site_name'] ?? config('app.name') }}">

    <!-- ================= THEME ================= -->
    <meta name="theme-color" content="{{ $siteSettings['theme_color'] ?? '#122636' }}">
    <meta name="application-name" content="{{ $siteSettings['site_name'] ?? config('app.name') }}">

    <!-- ================= PWA ================= -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">

    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title"
          content="{{ $siteSettings['site_name'] ?? config('app.name') }}">

    <meta name="msapplication-TileColor"
          content="{{ $siteSettings['theme_color'] ?? '#122636' }}">

    <!-- ================= ICONS ================= -->
    <link rel="icon"
          href="{{ $siteSettings['logo_mobile'] ?? asset('favicon.ico') }}"
          type="image/png">

    <link rel="apple-touch-icon"
          href="{{ $siteSettings['logo_mobile'] ?? asset('images/icons/apple-icon-180.png') }}">

    <!-- ================= OPEN GRAPH ================= -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $siteSettings['site_name'] ?? config('app.name') }}">
    <meta property="og:description"
          content="{{ $siteSettings['site_tagline'] ?? 'A Reunion of Dynamic Leaders' }}">
    <meta property="og:url" content="{{ $siteSettings['site_url'] ?? url('/') }}">
    <meta property="og:image"
          content="{{ $siteSettings['logo_desktop'] ?? asset('images/og-image.png') }}">

    <!-- ================= FONTS ================= -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap"
          rel="stylesheet">

    <!-- ================= CSS ================= -->
    <link rel="stylesheet" href="/user-panel/assets-panel/assets/css/iconsax.css">
    <link rel="stylesheet" href="/user-panel/assets-panel/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/user-panel/assets-panel/assets/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="/user-panel/assets-panel/assets/css/style.css">

    <!-- ================= INERTIA ================= -->
    @routes
    <script src="{{ mix('js/app.js') }}" defer></script>

    <!-- ================= SERVICE WORKER ================= -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/serviceworker.js');
            });
        }
    </script>
</head>

<body class="dark">
<div id="app" data-page="{{ json_encode($page) }}"></div>

@env('local')
    <script src="http://localhost:3000/browser-sync/browser-sync-client.js"></script>
@endenv

<!-- ================= JS ================= -->
<script src="/user-panel/assets-panel/assets/js/bootstrap.bundle.min.js"></script>
<script src="/user-panel/assets-panel/assets/js/swiper-bundle.min.js"></script>
<script src="/user-panel/assets-panel/assets/js/custom-swiper.js"></script>
<script src="/user-panel/assets-panel/assets/js/feather.min.js"></script>
<script src="/user-panel/assets-panel/assets/js/custom-feather.js"></script>
<script src="/user-panel/assets-panel/assets/js/iconsax.js"></script>
<script src="/user-panel/assets-panel/assets/js/script.js"></script>

</body>
</html>
