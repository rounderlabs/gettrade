<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <head>
        {{-- ===============================
            BASIC META
        =============================== --}}
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta charset="utf-8">

        <title inertia>
            {{ $siteSettings['site_name'] ?? config('app.name') }} Admin
        </title>

        @inertiaHead

        {{-- ===============================
            VIEWPORT & THEME
        =============================== --}}
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
        <meta name="color-scheme" content="light dark" />

        <meta
            name="theme-color"
            content="{{ $siteSettings['theme_color'] ?? '#007bff' }}"
            media="(prefers-color-scheme: light)"
        />
        <meta
            name="theme-color"
            content="{{ $siteSettings['theme_color_dark'] ?? '#1a1a1a' }}"
            media="(prefers-color-scheme: dark)"
        />

        {{-- ===============================
            PRIMARY SEO META
        =============================== --}}
        <meta
            name="title"
            content="{{ $siteSettings['site_name'] ?? config('app.name') }} | Admin Dashboard"
        />

        <meta
            name="description"
            content="{{ $siteSettings['site_tagline'] ?? 'Admin Panel' }}"
        />

        <meta
            name="keywords"
            content="{{ $siteSettings['site_keywords'] ?? 'admin,dashboard,management' }}"
        />

        <meta
            name="author"
            content="{{ $siteSettings['site_author'] ?? 'Admin' }}"
        />

        {{-- ===============================
            FAVICON
        =============================== --}}
        <link
            rel="icon"
            type="image/png"
            href="{{ asset($siteSettings['logo_mobile'] ?? '/favicon.ico') }}"
        />

        {{-- ===============================
            PRELOAD / PERFORMANCE
        =============================== --}}
        <link rel="preload" href="/assets/css/adminlte.min.css" as="style" />

        {{-- ===============================
            FONTS
        =============================== --}}
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
            crossorigin="anonymous"
            media="print"
            onload="this.media='all'"
        />

        {{-- ===============================
            THIRD PARTY CSS
        =============================== --}}
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
            crossorigin="anonymous"
        />

        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
            crossorigin="anonymous"
        />

        {{-- ===============================
            ADMIN THEME CSS
        =============================== --}}
        <link rel="stylesheet" href="/assets/css/style.css" />

        {{-- ===============================
            CHARTS (OPTIONAL)
        =============================== --}}
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
            crossorigin="anonymous"
        />

        {{-- ===============================
            INLINE WIDGET STYLES
        =============================== --}}
        <style>
            .card-widget {
                border: 0;
                position: relative;
            }

            .small-box {
                color: #fff;
            }
        </style>

        {{-- ===============================
            ZIGGY ROUTES
        =============================== --}}
        @routes

        {{-- ===============================
            INERTIA ADMIN JS
        =============================== --}}
        <script src="{{ mix('js/admin.js') }}" defer></script>
</head>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
{{--<div id="admin-app" data-page="{{ json_encode($page) }}"></div>--}}
@inertia


<script
    src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
    crossorigin="anonymous"
></script>
<!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
<script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    crossorigin="anonymous"
></script>
<!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
    crossorigin="anonymous"
></script>
<!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
<script src="/assets/js/adminlte.js"></script>
<!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
<script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
    };
    document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined) {
            OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                scrollbars: {
                    theme: Default.scrollbarTheme,
                    autoHide: Default.scrollbarAutoHide,
                    clickScroll: Default.scrollbarClickScroll,
                },
            });
        }
    });
</script>
<!--end::OverlayScrollbars Configure-->
</body>
</html>

