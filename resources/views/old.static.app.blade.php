<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content="The shuchak - A REUNION OF DYNAMIC LEADERS."/>
    <meta name="keywords" content="The shuchak - A REUNION OF DYNAMIC LEADERS"/>
    <meta name="author" content="The Shuchak"/>
{{--    <link rel="manifest" href="./manifest.json"/>--}}
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" href="theshuchak/assets/images/shuchak-logo.png" type="image/x-icon"></link>
    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    <link rel="apple-touch-icon" href="public/images/icons/apple-icon-180.png">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#122636"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name="apple-mobile-web-app-title" content="The shuchak - A REUNION OF DYNAMIC LEADERS"/>
    <meta name="msapplication-TileImage" content=theshuchak/assets/image/shuchak-logo.png"/>
    <meta name="msapplication-TileColor" content="#FFFFFF"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <!-- ✅ PWA + iOS Meta -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#007bff">

    <!-- iOS Specific -->
    <link rel="apple-touch-icon" href="/images/icons/manifest-icon-192.maskable.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="The Shuchak ">
    <meta name="msapplication-TileImage" content="/images/icons/manifest-icon-512.maskable.png">
    <meta name="msapplication-TileColor" content="#007bff">

    <!-- ✅ Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(function(registration) {
                        console.log('✅ ServiceWorker registered: ', registration.scope);
                    })
                    .catch(function(err) {
                        console.error('❌ ServiceWorker registration failed:', err);
                    });
            });
        }
    </script>


    <!--Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com"></link>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin></link>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&display=swap" rel="stylesheet"></link>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <!-- iconsax css -->
    <link rel="stylesheet" type="text/css" href="/user-panel/assets-panel/assets/css/iconsax.css"></link>


    <!-- bootstrap css -->
    <link rel="stylesheet" id="rtl-link" type="text/css"
          href="/user-panel/assets-panel/assets/css/bootstrap.min.css"></link>

    <!-- swiper css -->
    <link rel="stylesheet" type="text/css" href="/user-panel/assets-panel/assets/css/swiper-bundle.min.css"></link>

    <!-- Theme css -->
    <link rel="stylesheet" id="change-link" type="text/css" href="/user-panel/assets-panel/assets/css/style.css"></link>
    <script>
        import {feather} from "../../public/js/app";
        feather.replace();
    </script>
    @routes
    <script src="{{ mix('js/app.js') }}" defer></script>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/serviceworker.js');
        }
    </script>
</head>
<!-- ✅ iOS "Add to Home Screen" Banner -->
<div id="iosInstallPrompt"
     class="position-fixed bottom-0 start-0 end-0 bg-white text-center p-3 shadow-lg d-none"
     style="z-index: 9999;">
    <div class="d-flex align-items-center justify-content-between">
        <div class="text-start">
            <h6 class="mb-1 fw-bold text-dark">Install The Shuchak App</h6>
            <p class="small mb-0 text-muted">
                Tap <img src="/images/icons/download-app.png" width="18" class="mx-1"/>
                then <strong>Install App</strong>
            </p>
        </div>
        <button id="closeIosPrompt" class="btn btn-sm btn-outline-secondary">×</button>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const userAgent = window.navigator.userAgent.toLowerCase();
        const isIos = /iphone|ipad|ipod/.test(userAgent);
        const isInStandaloneMode = ('standalone' in window.navigator) && window.navigator.standalone;

        if (isIos && !isInStandaloneMode) {
            const prompt = document.getElementById("iosInstallPrompt");
            const closeBtn = document.getElementById("closeIosPrompt");

            prompt.classList.remove("d-none");

            closeBtn.addEventListener("click", () => {
                prompt.classList.add("d-none");
            });
        }
    });
</script>

<body class="light">
<div id="app" data-page="{{ json_encode($page) }}"></div>

@env ('local')
    <script src="http://localhost:3000/browser-sync/browser-sync-client.js"></script>
@endenv

<!-- pwa install app popup start -->
{{--<div class="offcanvas offcanvas-bottom addtohome-popup theme-offcanvas" tabindex="-1" id="offcanvas">--}}
{{--    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>--}}
{{--    <div class="offcanvas-body small">--}}
{{--        <div class="app-info">--}}
{{--            <img src="/user-panel/assets-panel/assets/images/logo/48.png" class="img-fluid" alt=""/>--}}
{{--            <div class="content">--}}
{{--                <h4>The Shuchak App</h4>--}}
{{--                <a href="#">www.The Shuchak-app.com</a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <a href="#!" class="btn theme-btn install-app btn-inline home-screen-btn m-0" id="installapp">Add to Home--}}
{{--            Screen</a>--}}
{{--    </div>--}}
{{--</div>--}}
<!-- pwa install app popup start -->

<!-- swiper js -->
<script src="/user-panel/assets-panel/assets/js/swiper-bundle.min.js"></script>
<script src="/user-panel/assets-panel/assets/js/custom-swiper.js"></script>

<!-- feather js -->
<script src="/user-panel/assets-panel/assets/js/feather.min.js"></script>
<script src="/user-panel/assets-panel/assets/js/custom-feather.js"></script>

<!-- iconsax js -->
<script src="/user-panel/assets-panel/assets/js/iconsax.js"></script>

<!-- bootstrap js -->
<script src="/user-panel/assets-panel/assets/js/bootstrap.bundle.min.js"></script>

<!-- homescreen popup js -->
<script src="/user-panel/assets-panel/assets/js/homescreen-popup.js"></script>

<!-- PWA offcanvas popup js -->
<script src="/user-panel/assets-panel/assets/js/offcanvas-popup.js"></script>

<!-- script js -->
<script src="/user-panel/assets-panel/assets/js/script.js"></script>


</body>
</html>
