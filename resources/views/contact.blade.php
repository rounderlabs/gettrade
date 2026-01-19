@extends('layouts.front')
@section('content')
    <div class="m-content money money-var" id="main-wrap">
        <div>
            <div id="home"></div>
            <div class="main-wrap">
                <div class="sidenav mobile-nav" id="slide-menu">
                    <div class="menu">
                        <ul class="collection">
                            <li class="collection-item" style="animation-duration: 0.25s"><a
                                    class="sidenav-close waves-effect menu-list" href="#promotions">Home</a></li>
                            <li class="collection-item" style="animation-duration: 0.5s"><a
                                    class="sidenav-close waves-effect menu-list" href="#feature">Open Account</a></li>
                            <li class="collection-item" style="animation-duration: 0.75s"><a
{{--                                    class="sidenav-close waves-effect menu-list" href="#benefit">benefit</a></li>--}}
                            <li class="collection-item" style="animation-duration: 1s"><a
                                    class="sidenav-close waves-effect menu-list" href="#testimonials">Our Products</a>
                            </li>
                            <li class="collection-item" style="animation-duration: 1s"><a class="waves-effect menu-list"
                                                                                          href="{{ route('contact') }}">contact</a>
                            </li>
                        </ul>
                        <hr class="divider-sidebar">
                        <ul class="collection">
                            <li class="collection-item" style="animation-duration: 1s"><a
                                    class="sidenav-close waves-effect menu-list" href="{{ route('login') }}">log in</a>
                            </li>
                            <li class="collection-item" style="animation-duration: 1s"><a
                                    class="sidenav-close waves-effect menu-list"
                                    href="{{ route('register') }}">register</a></li>
                        </ul>
                    </div>
                </div><!-- ##### HEADER #####-->

                <header class="app-bar header" id="header">
                    <div class="container fixed-width-lg-up">
                        <div class="header-content">
                            <nav class="nav-logo nav-menu">
                                <button class="mobile-menu btn-icon waves-effect hamburger hamburger--spin show-md-down"
                                        id="mobile_menu" type="button">
                                    <span class="hamburger-box"><span class="bar hamburger-inner"></span></span>
                                </button>
                                <div class="logo scrollnav">
                                    <a href="#banner"><img src="frontend/images/zzz-logo.png" alt="logo"/></a>
                                </div>
                                <div>
                                    <div class="scrollactive-nav show-lg-up scrollnav">
                                        <ul>
                                            <li class="d-none"><a href="#banner"></a></li>
                                            <li><a class="btn btn-flat anchor-link waves-effect" href="#banner">Home</a>
                                            </li>
                                            <li><a class="btn btn-flat anchor-link waves-effect"
                                                   href="#feature">Open Account</a></li>
{{--                                            <li><a class="btn btn-flat anchor-link waves-effect"--}}
{{--                                                   href="#benefit">benefit</a></li>--}}
                                            <li><a class="btn btn-flat anchor-link waves-effect" href="#testimonials">Our Products</a>
                                            </li>
                                            <li><a class="btn btn-flat anchor-link waves-effect"
                                                   href="{{route('contact')}}">contact</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </nav>
                            <div class="hidden-md-down"><span class="divider"></span></div>
                            <nav class="nav-menu nav-auth">
                                <div class="hidden-xs-down">
                                    <a class="btn btn-flat text-btn waves-effect" href="{{ route('login') }}">log in</a>
                                    <a class="btn secondary button waves-effect"
                                       href="{{ route('register') }}">register</a>
                                </div>

                            </nav>
                        </div>
                    </div>
                </header>
                <!-- ##### END HEADER #####-->
                <main class="container-wrap">
                    <!-- ##### BANNER #####-->
                    <section id="banner">
                        <div class="root">
                            <div class="canvas-wrap">
                                <div class="overlay-banner">
                                    <div class="deco-inner">
                                        <div class="particle-background" id="particles_backgrond"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="container fixed-width">
                                <div class="banner-wrap">
                                    <div class="row spacing6 align-items-center">
                                        <div class="col-md-6 pa-6">
                                            <div class="text">
                                                <h4 class="use-text-title2">Invest Smarter With<br>
                                                    ZZZ Securities.</h4>
                                                <p class="use-text-subtitle2">Trade in Share Market with ZZZ Securities. LIVE BSE/NSE, Nifty Sensex Share Price, Stock Exchange, </p>
                                            </div>
                                            <div class="btn-area">
                                                <a class="button btn secondary btn-large mq-xs-down waves-effect"
                                                   href="{{route('register')}}" data-class="block">Get Started</a>
{{--                                                <a class="btn btn-outlined btn-large invert mq-xs-down waves-effect"--}}
{{--                                                   href="#" data-class="block">View Market</a>--}}
                                            </div>
                                        </div>
                                        <div class="col-md-6 pa-6">
                                            <figure class="object-art">
                                                <img style="width:100%" src="frontend/images/banner-1.png" alt="ZZZ Securities"/>
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                                <div class="deco-bottom back">
                                    <svg>
                                        <g stroke="none" stroke-width="1" fill-rule="evenodd">
                                            <path
                                                d="M1375.45284,0.352502919 L1375.45284,347.032921 L0.452844281,347.032921 L0.452844281,188.611725 C15.78443,205.768227 36.8652613,217.445223 60.4520853,220.920666 L758.888594,323.833029 C780.839654,327.067447 803.243038,322.909542 822.571664,312.013915 L1375.45284,0.352502919 Z"></path>
                                        </g>
                                    </svg>
                                </div>
                                <div class="deco-bottom cover">
                                    <svg>
                                        <g stroke="none" stroke-width="1" fill-rule="evenodd">
                                            <path
                                                d="M1375.45284,0.352502919 L1375.45284,347.032921 L0.452844281,347.032921 L0.452844281,188.611725 C15.78443,205.768227 36.8652613,217.445223 60.4520853,220.920666 L758.888594,323.833029 C780.839654,327.067447 803.243038,322.909542 822.571664,312.013915 L1375.45284,0.352502919 Z"></path>
                                        </g>
                                    </svg>
                                </div>
                            </div>

                        </div>
                    </section><!-- ##### END BANNER #####-->
                    <!-- ##### PROMOTIONS #####-->
{{--                    <section id="promotions">--}}
{{--                        <div class="root">--}}
{{--                            <div class="slider-wrap">--}}
{{--                                <div class="anim-slider" id="promotion_slider">--}}
{{--                                    <div class="slide slider-content anim-slide">frontend/images/alliv.png--}}
{{--                                        <section class="inner item even">--}}
{{--                                            <div class="row">--}}
{{--                                                <div class="col-lg-4 hidden-md-down">&nbsp;</div>--}}
{{--                                                <div class="col-lg-7">--}}
{{--                                                    <div class="hidden-md-down">--}}
{{--                                                        <div class="img-wrap">--}}
{{--                                                            <div class="decoration">--}}

{{--                                                            </div>--}}
{{--                                                            <figure class="image">--}}
{{--                                                                <img src="frontend/images/iv3.png"--}}
{{--                                                                     alt=""/>--}}
{{--                                                            </figure>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="card paper">--}}
{{--                                                        <h2>--}}
{{--                                                            <a class="waves-effect" href="#">Validator</a>--}}
{{--                                                        </h2>--}}
{{--                                                        <p>Validators can earn up to 30% Monthly Percentage Yield (MPY).</p>--}}
{{--                                                        <section class="time">--}}


{{--                                                        </section>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </section>--}}
{{--                                    </div>--}}
{{--                                    <div class="slide slider-content anim-slide">--}}
{{--                                        <section class="inner item odd">--}}
{{--                                            <div class="row">--}}
{{--                                                <div class="col-lg-4 hidden-md-down">&nbsp;</div>--}}
{{--                                                <div class="col-lg-7">--}}
{{--                                                    <div class="hidden-md-down">--}}
{{--                                                        <div class="img-wrap">--}}
{{--                                                            <div class="decoration">--}}

{{--                                                            </div>--}}
{{--                                                            <figure class="image">--}}
{{--                                                                <img src="frontend/images/middlepro.png"--}}
{{--                                                                     alt=""/>--}}
{{--                                                            </figure>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="card paper">--}}
{{--                                                        <h2>--}}
{{--                                                            <a class="waves-effect" href="#">Validators Pro</a>--}}
{{--                                                        </h2>--}}
{{--                                                        <p>Validators can earn up to 30% Monthly Percentage Yield (MPY).</p>--}}
{{--                                                        <section class="time">--}}

{{--                                                        </section>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </section>--}}
{{--                                    </div>--}}
{{--                                    <div class="slide slider-content anim-slide">--}}
{{--                                        <section class="inner item even">--}}
{{--                                            <div class="row">--}}
{{--                                                <div class="col-lg-4 hidden-md-down">&nbsp;</div>--}}
{{--                                                <div class="col-lg-7">--}}
{{--                                                    <div class="hidden-md-down">--}}
{{--                                                        <div class="img-wrap">--}}
{{--                                                            <div class="decoration">--}}

{{--                                                            </div>--}}
{{--                                                            <figure class="image">--}}
{{--                                                                <img src="frontend/images/promax.png"--}}
{{--                                                                     alt=""/>--}}
{{--                                                            </figure>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="card paper">--}}
{{--                                                        <h2>--}}
{{--                                                            <a class="waves-effect" href="#">Validator Pro Max</a>--}}
{{--                                                        </h2>--}}
{{--                                                        <p>Validators can earn up to 30% Monthly Percentage Yield (MPY).</p>--}}
{{--                                                        <section class="time">--}}

{{--                                                        </section>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </section>--}}
{{--                                    </div>--}}
{{--                                    <div class="anim-arrows">--}}
{{--                                        <a class="anim-arrows-prev" href="#"><i--}}
{{--                                                class="material-icons icon">arrow_back</i></a>--}}
{{--                                        <a class="anim-arrows-next" href="#"><i class="material-icons icon">arrow_forward</i></a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </section><!-- ##### END PROMOTIONS #####-->--}}
{{--                    <!-- ##### FEATURE #####-->--}}
                    <section class="space-top-short-md-down" id="feature">
                        <div class="root" id="feature_parallax">
                            <!-- ##### PARALLAX FEATURE #####-->
                            <div class="parallax-wrap dots-wrap">
                                <div class="inner-parallax large">
                                    <div class="hexa-wrap">
                                        <div data-enllax-ratio="-0.3" data-enllax-type="foreground">
                                            <div class="parallax-figure right-top-back big">
                                                <div>
                                                    <svg class="hexa-back">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                           fill-rule="evenodd">
                                                            <g transform="translate(227.651059, 155.535288) rotate(-27.000000) translate(-227.651059, -155.535288) translate(56.651059, -60.964712)"
                                                               fill="#000000">
                                                                <path
                                                                    d="M0.44343974,128.791576 C0.44343974,135.606019 4.09101735,141.903161 10.0124295,145.310383 L73.4952406,181.840468 C79.4166528,185.24769 86.711808,185.24769 92.6332201,181.840468 L156.116031,145.310383 C162.037443,141.903161 165.685021,135.606019 165.685021,128.791576 L165.685021,55.7310994 C165.685021,48.9163504 162.037443,42.6195139 156.116031,39.2119866 L92.6332201,2.6819012 C86.711808,-0.725320492 79.4166528,-0.725320492 73.4952406,2.6819012 L10.0124295,39.2119866 C4.09101735,42.6195139 0.44343974,48.9163504 0.44343974,55.7310994 L0.44343974,128.791576 Z"></path>
                                                                <path
                                                                    d="M95.127122,349.251509 C95.127122,359.454656 100.576382,368.883258 109.422613,373.984832 L204.262085,428.680693 C213.108316,433.782267 224.006836,433.782267 232.853067,428.680693 L327.692539,373.984832 C336.53877,368.883258 341.98803,359.454656 341.98803,349.251509 L341.98803,239.859328 C341.98803,229.655723 336.53877,220.227579 327.692539,215.125548 L232.853067,160.429686 C224.006836,155.328112 213.108316,155.328112 204.262085,160.429686 L109.422613,215.125548 C100.576382,220.227579 95.127122,229.655723 95.127122,239.859328 L95.127122,349.251509 Z"></path>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div data-enllax-ratio="-0.2" data-enllax-type="foreground">
                                            <div class="parallax-figure right-top-front small">
                                                <div>
                                                    <svg width="585px" height="151px" viewbox="0 0 585 151"
                                                         version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                           fill-rule="evenodd">
                                                            <g transform="translate(-143.000000, -88.000000)">
                                                                <g transform="translate(132.623862, 70.912032)">
                                                                    <path
                                                                        d="M520.561173,148.252603 C520.561173,151.131802 522.101234,153.792433 524.601342,155.232033 L551.404725,170.666509 C553.904833,172.106108 556.984955,172.106108 559.485063,170.666509 L586.288446,155.232033 C588.788553,153.792433 590.328615,151.131802 590.328615,148.252603 L590.328615,117.383522 C590.328615,114.504194 588.788553,111.843692 586.288446,110.403963 L559.485063,94.9694871 C556.984955,93.5298879 553.904833,93.5298879 551.404725,94.9694871 L524.601342,110.403963 C522.101234,111.843692 520.561173,114.504194 520.561173,117.383522 L520.561173,148.252603 Z"
                                                                        fill="url(#hexaLinearGradient-2)"
                                                                        transform="translate(555.444894, 132.817998) rotate(-330.000000) translate(-555.444894, -132.817998)"></path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hexa-wrap">
                                        <div data-enllax-ratio="-0.15" data-enllax-type="foreground">
                                            <div class="parallax-figure left-bottom-back big">
                                                <div>
                                                    <svg class="hexa-back">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                           fill-rule="evenodd">
                                                            <g transform="translate(-82.000000, -68.000000)"
                                                               fill="#000000">
                                                                <g transform="translate(203.500000, 201.352321) rotate(-27.000000) translate(-203.500000, -201.352321) translate(51.000000, 54.000000)">
                                                                    <path
                                                                        d="M0.251779512,246.826389 C0.251779512,252.66427 3.37663097,258.058983 8.44945884,260.977923 L62.8346909,292.272964 C67.9075188,295.191905 74.1572217,295.191905 79.2300496,292.272964 L133.615282,260.977923 C138.688109,258.058983 141.812961,252.66427 141.812961,246.826389 L141.812961,184.236046 C141.812961,178.397903 138.688109,173.003452 133.615282,170.08425 L79.2300496,138.789209 C74.1572217,135.870269 67.9075188,135.870269 62.8346909,138.789209 L8.44945884,170.08425 C3.37663097,173.003452 0.251779512,178.397903 0.251779512,184.236046 L0.251779512,246.826389 Z"></path>
                                                                    <path
                                                                        d="M82.1856754,110.412887 C82.1856754,116.250768 85.3105269,121.645481 90.3833548,124.564421 L144.768587,155.859462 C149.841415,158.778403 156.091118,158.778403 161.163945,155.859462 L215.549178,124.564421 C220.622005,121.645481 223.746857,116.250768 223.746857,110.412887 L223.746857,47.8225436 C223.746857,41.984401 220.622005,36.58995 215.549178,33.6707478 L161.163945,2.37570687 C156.091118,-0.54323354 149.841415,-0.54323354 144.768587,2.37570687 L90.3833548,33.6707478 C85.3105269,36.58995 82.1856754,41.984401 82.1856754,47.8225436 L82.1856754,110.412887 Z"></path>
                                                                    <path
                                                                        d="M163.261625,246.826389 C163.261625,252.66427 166.386476,258.058983 171.459304,260.977923 L225.844536,292.272964 C230.917364,295.191905 237.167067,295.191905 242.239895,292.272964 L296.625127,260.977923 C301.697955,258.058983 304.822806,252.66427 304.822806,246.826389 L304.822806,184.236046 C304.822806,178.397903 301.697955,173.003452 296.625127,170.08425 L242.239895,138.789209 C237.167067,135.870269 230.917364,135.870269 225.844536,138.789209 L171.459304,170.08425 C166.386476,173.003452 163.261625,178.397903 163.261625,184.236046 L163.261625,246.826389 Z"></path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div data-enllax-ratio="-0.1" data-enllax-type="foreground">
                                            <div class="parallax-figure left-bottom-front small">
                                                <div>
                                                    <svg width="585px" height="151px" viewbox="0 0 585 151"
                                                         version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                           fill-rule="evenodd">
                                                            <g transform="translate(-143.000000, -88.000000)">
                                                                <g transform="translate(132.623862, 70.912032)">
                                                                    <path
                                                                        d="M15,67.3628158 C15,70.2420142 16.5400613,72.9026456 19.0401691,74.3422448 L45.8435518,89.7767212 C48.3436597,91.2163204 51.4237822,91.2163204 53.9238901,89.7767212 L80.7272727,74.3422448 C83.2273806,72.9026456 84.7674419,70.2420142 84.7674419,67.3628158 L84.7674419,36.4937339 C84.7674419,33.6144064 83.2273806,30.9539041 80.7272727,29.5141758 L53.9238901,14.0796994 C51.4237822,12.6401002 48.3436597,12.6401002 45.8435518,14.0796994 L19.0401691,29.5141758 C16.5400613,30.9539041 15,33.6144064 15,36.4937339 L15,67.3628158 Z"
                                                                        fill="url(#hexaLinearGradient-3)"
                                                                        transform="translate(49.883721, 51.928210) rotate(-330.000000) translate(-49.883721, -51.928210)"></path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ##### END PARALLAX FEATURE #####-->
                            <!-- ##### MAIN FEATURE #####-->
                            <div class="main-feature">
                                <div class="modal video-popup" id="video_modal">
                                    <div class="modal-content">
                                        <h4>Digital currency leads at market in the right amount.</h4>
                                        <button class="btn-icon modal-close waves-effect"><i class="material-icons">close</i>
                                        </button>
                                        <div class="text-center">
                                            <div id="video_iframe"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="container fixed-width">
                                    <div class="row spacing6">
                                        <div class="col-md-12 px-6">
                                            <div class="title-main align-left">
                                                <div class="deco-title">
                                                    <svg width="38px" height="43px" viewbox="0 0 38 43" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                           fill-rule="evenodd">
                                                            <path
                                                                d="M0,30.0245126 C0,31.6146901 0.838820037,33.0841534 2.20054545,33.8792422 L16.7994545,42.4036834 C18.16118,43.1987722 19.83882,43.1987722 21.2005455,42.4036834 L35.7994545,33.8792422 C37.16118,33.0841534 38,31.6146901 38,30.0245126 L38,12.9755587 C38,11.3853099 37.16118,9.91591793 35.7994545,9.12075784 L21.2005455,0.596316588 C19.83882,-0.198772196 18.16118,-0.198772196 16.7994545,0.596316588 L2.20054545,9.12075784 C0.838820037,9.91591793 0,11.3853099 0,12.9755587 L0,30.0245126 Z"
                                                                fill="url(#titleLinearGradient-1)"></path>
                                                        </g>
                                                    </svg>
                                                </div>

                                            </div>
                                            <img style="width:100%" src="frontend/images/openDemat.png">
                                        </div>

                                    </div>
                                </div>
                            </div><!-- ##### END MAIN FEATURE #####-->
                            <!-- ##### MORE FEATURE #####-->
{{--                            <div class="more-feature">--}}
{{--                                <div class="container fixed-width-md-up">--}}
{{--                                    <div class="item">--}}
{{--                                        <div class="row spacing6">--}}
{{--                                            <div class="col-md-6 py-md-0 px-6">--}}
{{--                                                <div class="text">--}}
{{--                                                    <span class="ion-ios-locked-outline"></span>--}}
{{--                                                    <div class="title-main align-left">--}}
{{--                                                        <div class="deco-title">--}}
{{--                                                            <svg width="38px" height="43px" viewbox="0 0 38 43"--}}
{{--                                                                 version="1.1">--}}
{{--                                                                <g stroke="none" stroke-width="1" fill="none"--}}
{{--                                                                   fill-rule="evenodd">--}}
{{--                                                                    <path--}}
{{--                                                                        d="M0,30.0245126 C0,31.6146901 0.838820037,33.0841534 2.20054545,33.8792422 L16.7994545,42.4036834 C18.16118,43.1987722 19.83882,43.1987722 21.2005455,42.4036834 L35.7994545,33.8792422 C37.16118,33.0841534 38,31.6146901 38,30.0245126 L38,12.9755587 C38,11.3853099 37.16118,9.91591793 35.7994545,9.12075784 L21.2005455,0.596316588 C19.83882,-0.198772196 18.16118,-0.198772196 16.7994545,0.596316588 L2.20054545,9.12075784 C0.838820037,9.91591793 0,11.3853099 0,12.9755587 L0,30.0245126 Z"--}}
{{--                                                                        fill="url(#titleLinearGradient-1)"></path>--}}
{{--                                                                </g>--}}
{{--                                                            </svg>--}}
{{--                                                        </div>--}}
{{--                                                        <h4>The Most Powerful Validator Nodes--}}
{{--                                                        </h4>--}}
{{--                                                    </div>--}}
{{--                                                    <p class="use-text-subtitle2 text-center text-lg-start">Our flagship product is a robust and comprehensive solution that automates the validation of Crypto Transaction, such as TRON SCAN, BITCOIN, ETHER 2.0, and AVAX, Transaction statements. It employs advanced algorithms and machine learning techniques to identify discrepancies, errors, and anomalies, ensuring the accuracy and integrity of Transactions information over the Blockchain Network.</p>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="col-md-6 py-md-0 px-6">--}}
{{--                                                <div class="wow fadeInLeftShort" data-wow-delay="0.3s"--}}
{{--                                                     data-wow-duration="0.3s">--}}
{{--                                                    <figure class="illustration">--}}
{{--                                                        <img src="frontend/images/ivbanner.png"--}}
{{--                                                             alt="feature"/>--}}
{{--                                                    </figure>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div><!-- ##### END MORE FEATURE #####-->--}}
                        </div>
                    </section><!-- ##### END FEATURE #####-->
                    <!-- ##### BENEFIT #####-->
{{--                    <section class="space-top-short" id="benefit">--}}
{{--                        <div class="root">--}}
{{--                            <div class="parallax-img">--}}
{{--                                <div class="hidden-sm-down">--}}
{{--                                    <div class="parallax" data-enllax-ratio="0.1"></div>--}}
{{--                                    <div class="parallax-props"></div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="container fixed-width-lg-up">--}}
{{--                                <div class="wrapper">--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-md-5 pa-md-0">--}}
{{--                                            <div class="desc">--}}
{{--                                                <h3 class="use-text-title2 text-center text-md-start">Types Of Insta-Validators</h3>--}}
{{--                                                <ul class="list">--}}
{{--                                                    <li>Validator</li>--}}
{{--                                                    <li>Validator Pro</li>--}}
{{--                                                    <li>Validator Pro Max</li>--}}
{{--                                                </ul>--}}
{{--                                                <h3 class="use-text-title2 text-center text-md-start">All three Validators are used to generate revenue according to their efficiency</h3>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-7">--}}
{{--                                            <div>--}}
{{--                                                <figure class="img">--}}
{{--                                                    <img src="frontend/images/allvalidators.png"--}}
{{--                                                         alt="benefit"/>--}}
{{--                                                </figure>--}}
{{--                                            </div>--}}
{{--                                            <div class="deco">--}}
{{--                                                <svg width="585px" height="151px" viewbox="0 0 585 151" version="1.1">--}}
{{--                                                    <defs>--}}
{{--                                                        <lineargradient id="benefitLinearGradient-2" x1="66.8412844%"--}}
{{--                                                                        y1="30.62426%" x2="-21.0581447%" y2="100%">--}}
{{--                                                            <stop stop-color="#4CAF50" offset="0%"></stop>--}}
{{--                                                            <stop stop-color="#C8E6C9" offset="100%"></stop>--}}
{{--                                                        </lineargradient>--}}
{{--                                                        <lineargradient id="benefitLinearGradient-3" x1="66.8412844%"--}}
{{--                                                                        y1="30.62426%" x2="-21.0581447%" y2="100%">--}}
{{--                                                            <stop stop-color="#FFA000" offset="0%"></stop>--}}
{{--                                                            <stop stop-color="#FFECB3" offset="100%"></stop>--}}
{{--                                                        </lineargradient>--}}
{{--                                                    </defs>--}}
{{--                                                    <g id="Benefit-1" stroke="none" stroke-width="1" fill="none"--}}
{{--                                                       fill-rule="evenodd">--}}
{{--                                                        <g id="Benefit-2"--}}
{{--                                                           transform="translate(-143.000000, -88.000000)">--}}
{{--                                                            <g id="Benefit-3"--}}
{{--                                                               transform="translate(132.623862, 70.912032)">--}}
{{--                                                                <path id="BenefitFill-1"--}}
{{--                                                                      d="M15,67.3628158 C15,70.2420142 16.5400613,72.9026456 19.0401691,74.3422448 L45.8435518,89.7767212 C48.3436597,91.2163204 51.4237822,91.2163204 53.9238901,89.7767212 L80.7272727,74.3422448 C83.2273806,72.9026456 84.7674419,70.2420142 84.7674419,67.3628158 L84.7674419,36.4937339 C84.7674419,33.6144064 83.2273806,30.9539041 80.7272727,29.5141758 L53.9238901,14.0796994 C51.4237822,12.6401002 48.3436597,12.6401002 45.8435518,14.0796994 L19.0401691,29.5141758 C16.5400613,30.9539041 15,33.6144064 15,36.4937339 L15,67.3628158 Z"--}}
{{--                                                                      fill="url(#benefitLinearGradient-3)"--}}
{{--                                                                      transform="translate(49.883721, 51.928210) rotate(-330.000000) translate(-49.883721, -51.928210)"></path>--}}
{{--                                                                <path id="BenefitFill-2"--}}
{{--                                                                      d="M520.561173,148.252603 C520.561173,151.131802 522.101234,153.792433 524.601342,155.232033 L551.404725,170.666509 C553.904833,172.106108 556.984955,172.106108 559.485063,170.666509 L586.288446,155.232033 C588.788553,153.792433 590.328615,151.131802 590.328615,148.252603 L590.328615,117.383522 C590.328615,114.504194 588.788553,111.843692 586.288446,110.403963 L559.485063,94.9694871 C556.984955,93.5298879 553.904833,93.5298879 551.404725,94.9694871 L524.601342,110.403963 C522.101234,111.843692 520.561173,114.504194 520.561173,117.383522 L520.561173,148.252603 Z"--}}
{{--                                                                      fill="url(#benefitLinearGradient-2)"--}}
{{--                                                                      transform="translate(555.444894, 132.817998) rotate(-330.000000) translate(-555.444894, -132.817998)"></path>--}}
{{--                                                            </g>--}}
{{--                                                        </g>--}}
{{--                                                    </g>--}}
{{--                                                </svg>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </section><!-- ##### END BENEFIT #####-->--}}

                    <!-- ##### TESTIMONIALS #####-->
                    <section class="space-top" id="testimonials">
                        <div class="root">
                            <div class="container fixed-width-lg-up">
                                <div class="title-main align-center">
                                    <div class="deco-title">
                                        <svg width="38px" height="43px" viewbox="0 0 38 43" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <path
                                                    d="M0,30.0245126 C0,31.6146901 0.838820037,33.0841534 2.20054545,33.8792422 L16.7994545,42.4036834 C18.16118,43.1987722 19.83882,43.1987722 21.2005455,42.4036834 L35.7994545,33.8792422 C37.16118,33.0841534 38,31.6146901 38,30.0245126 L38,12.9755587 C38,11.3853099 37.16118,9.91591793 35.7994545,9.12075784 L21.2005455,0.596316588 C19.83882,-0.198772196 18.16118,-0.198772196 16.7994545,0.596316588 L2.20054545,9.12075784 C0.838820037,9.91591793 0,11.3853099 0,12.9755587 L0,30.0245126 Z"
                                                    fill="url(#titleLinearGradient-1)"></path>
                                            </g>
                                        </svg>
                                    </div>
                                    <h4>OUR PRODUCTS</h4>
                                </div>
                                <p class="use-text-subtitle2 text-center">ZZZ Securities</p>
                                <div class="row spacing6 justify-content-center">
                                    <div class="col-md-3 my-6">
                                        <div class="item">
                                            <div class="test-content">
                                                <div class="testimonial-card">
                                                    <div class="card paper">
                                                        <div class="avatar-img avatar">
                                                            <img src="frontend/images/zzz-cube-logo.png"
                                                                 alt="avatar"/>
                                                        </div>
                                                        <div class="">
                                                            <p class="package-color">D'Mat & Trading</p>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 my-6">
                                        <div class="item">
                                            <div class="test-content">
                                                <div class="testimonial-card">
                                                    <div class="card paper">
                                                        <div class="avatar-img avatar">
                                                            <img src="frontend/images/zzz-cube-logo.png"
                                                                 alt="avatar"/>
                                                        </div>
                                                        <div class="">
                                                            <p class="package-color">Mutual Funds / Insurance</p>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 my-6">
                                        <div class="item">
                                            <div class="test-content">
                                                <div class="testimonial-card">
                                                    <div class="card paper">
                                                        <div class="avatar-img avatar">
                                                            <img src="frontend/images/zzz-cube-logo.png"
                                                                 alt="avatar"/>
                                                        </div>
                                                        <div class="">
                                                            <p class="package-color">Pre-IPO / Unlisted Stock</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3 my-6">
                                        <div class="item">
                                            <div class="test-content">
                                                <div class="testimonial-card">
                                                    <div class="card paper">
                                                        <div class="avatar-img avatar">
                                                            <img src="frontend/images/zzz-cube-logo.png"
                                                                 alt="avatar"/>
                                                        </div>
                                                        <div class="">
                                                            <p class="package-color">IEPF Recovery Of Stock</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </section><!-- ##### END TESTIMONIALS #####-->
                    <!-- ##### FAQ #####-->
                    <section class="space-top" id="faq">
                        <div class="root">
                            <div class="parallax">
                                <div class="parallax-wrap dots-wrap">
                                    <div class="inner-parallax large">
                                        <div class="hexa-wrap">
                                            <div data-enllax-ratio="-0.3" data-enllax-type="foreground">
                                                <div class="parallax-figure right-top-back big">
                                                    <div>
                                                        <svg class="hexa-back">
                                                            <g stroke="none" stroke-width="1" fill="none"
                                                               fill-rule="evenodd">
                                                                <g transform="translate(227.651059, 155.535288) rotate(-27.000000) translate(-227.651059, -155.535288) translate(56.651059, -60.964712)"
                                                                   fill="#000000">
                                                                    <path
                                                                        d="M0.44343974,128.791576 C0.44343974,135.606019 4.09101735,141.903161 10.0124295,145.310383 L73.4952406,181.840468 C79.4166528,185.24769 86.711808,185.24769 92.6332201,181.840468 L156.116031,145.310383 C162.037443,141.903161 165.685021,135.606019 165.685021,128.791576 L165.685021,55.7310994 C165.685021,48.9163504 162.037443,42.6195139 156.116031,39.2119866 L92.6332201,2.6819012 C86.711808,-0.725320492 79.4166528,-0.725320492 73.4952406,2.6819012 L10.0124295,39.2119866 C4.09101735,42.6195139 0.44343974,48.9163504 0.44343974,55.7310994 L0.44343974,128.791576 Z"></path>
                                                                    <path
                                                                        d="M95.127122,349.251509 C95.127122,359.454656 100.576382,368.883258 109.422613,373.984832 L204.262085,428.680693 C213.108316,433.782267 224.006836,433.782267 232.853067,428.680693 L327.692539,373.984832 C336.53877,368.883258 341.98803,359.454656 341.98803,349.251509 L341.98803,239.859328 C341.98803,229.655723 336.53877,220.227579 327.692539,215.125548 L232.853067,160.429686 C224.006836,155.328112 213.108316,155.328112 204.262085,160.429686 L109.422613,215.125548 C100.576382,220.227579 95.127122,229.655723 95.127122,239.859328 L95.127122,349.251509 Z"></path>
                                                                </g>
                                                            </g>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <div data-enllax-ratio="-0.2" data-enllax-type="foreground">
                                                <div class="parallax-figure right-top-front small">
                                                    <div>
                                                        <svg width="585px" height="151px" viewbox="0 0 585 151"
                                                             version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none"
                                                               fill-rule="evenodd">
                                                                <g transform="translate(-143.000000, -88.000000)">
                                                                    <g transform="translate(132.623862, 70.912032)">
                                                                        <path
                                                                            d="M520.561173,148.252603 C520.561173,151.131802 522.101234,153.792433 524.601342,155.232033 L551.404725,170.666509 C553.904833,172.106108 556.984955,172.106108 559.485063,170.666509 L586.288446,155.232033 C588.788553,153.792433 590.328615,151.131802 590.328615,148.252603 L590.328615,117.383522 C590.328615,114.504194 588.788553,111.843692 586.288446,110.403963 L559.485063,94.9694871 C556.984955,93.5298879 553.904833,93.5298879 551.404725,94.9694871 L524.601342,110.403963 C522.101234,111.843692 520.561173,114.504194 520.561173,117.383522 L520.561173,148.252603 Z"
                                                                            fill="url(#hexaLinearGradient-2)"
                                                                            transform="translate(555.444894, 132.817998) rotate(-330.000000) translate(-555.444894, -132.817998)"></path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="hexa-wrap">
                                            <div data-enllax-ratio="-0.15" data-enllax-type="foreground">
                                                <div class="parallax-figure left-bottom-back big">
                                                    <div>
                                                        <svg class="hexa-back">
                                                            <g stroke="none" stroke-width="1" fill="none"
                                                               fill-rule="evenodd">
                                                                <g transform="translate(-82.000000, -68.000000)"
                                                                   fill="#000000">
                                                                    <g transform="translate(203.500000, 201.352321) rotate(-27.000000) translate(-203.500000, -201.352321) translate(51.000000, 54.000000)">
                                                                        <path
                                                                            d="M0.251779512,246.826389 C0.251779512,252.66427 3.37663097,258.058983 8.44945884,260.977923 L62.8346909,292.272964 C67.9075188,295.191905 74.1572217,295.191905 79.2300496,292.272964 L133.615282,260.977923 C138.688109,258.058983 141.812961,252.66427 141.812961,246.826389 L141.812961,184.236046 C141.812961,178.397903 138.688109,173.003452 133.615282,170.08425 L79.2300496,138.789209 C74.1572217,135.870269 67.9075188,135.870269 62.8346909,138.789209 L8.44945884,170.08425 C3.37663097,173.003452 0.251779512,178.397903 0.251779512,184.236046 L0.251779512,246.826389 Z"></path>
                                                                        <path
                                                                            d="M82.1856754,110.412887 C82.1856754,116.250768 85.3105269,121.645481 90.3833548,124.564421 L144.768587,155.859462 C149.841415,158.778403 156.091118,158.778403 161.163945,155.859462 L215.549178,124.564421 C220.622005,121.645481 223.746857,116.250768 223.746857,110.412887 L223.746857,47.8225436 C223.746857,41.984401 220.622005,36.58995 215.549178,33.6707478 L161.163945,2.37570687 C156.091118,-0.54323354 149.841415,-0.54323354 144.768587,2.37570687 L90.3833548,33.6707478 C85.3105269,36.58995 82.1856754,41.984401 82.1856754,47.8225436 L82.1856754,110.412887 Z"></path>
                                                                        <path
                                                                            d="M163.261625,246.826389 C163.261625,252.66427 166.386476,258.058983 171.459304,260.977923 L225.844536,292.272964 C230.917364,295.191905 237.167067,295.191905 242.239895,292.272964 L296.625127,260.977923 C301.697955,258.058983 304.822806,252.66427 304.822806,246.826389 L304.822806,184.236046 C304.822806,178.397903 301.697955,173.003452 296.625127,170.08425 L242.239895,138.789209 C237.167067,135.870269 230.917364,135.870269 225.844536,138.789209 L171.459304,170.08425 C166.386476,173.003452 163.261625,178.397903 163.261625,184.236046 L163.261625,246.826389 Z"></path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <div data-enllax-ratio="-0.1" data-enllax-type="foreground">
                                                <div class="parallax-figure left-bottom-front small">
                                                    <div>
                                                        <svg width="585px" height="151px" viewbox="0 0 585 151"
                                                             version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none"
                                                               fill-rule="evenodd">
                                                                <g transform="translate(-143.000000, -88.000000)">
                                                                    <g transform="translate(132.623862, 70.912032)">
                                                                        <path
                                                                            d="M15,67.3628158 C15,70.2420142 16.5400613,72.9026456 19.0401691,74.3422448 L45.8435518,89.7767212 C48.3436597,91.2163204 51.4237822,91.2163204 53.9238901,89.7767212 L80.7272727,74.3422448 C83.2273806,72.9026456 84.7674419,70.2420142 84.7674419,67.3628158 L84.7674419,36.4937339 C84.7674419,33.6144064 83.2273806,30.9539041 80.7272727,29.5141758 L53.9238901,14.0796994 C51.4237822,12.6401002 48.3436597,12.6401002 45.8435518,14.0796994 L19.0401691,29.5141758 C16.5400613,30.9539041 15,33.6144064 15,36.4937339 L15,67.3628158 Z"
                                                                            fill="url(#hexaLinearGradient-3)"
                                                                            transform="translate(49.883721, 51.928210) rotate(-330.000000) translate(-49.883721, -51.928210)"></path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="container fixed-width">
                                <div class="row spacing6">
                                    <div class="col-md-6 pa-6">
                                        <div class="title-main align-left">
                                            <div class="deco-title">
                                                <svg width="38px" height="43px" viewbox="0 0 38 43" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <path
                                                            d="M0,30.0245126 C0,31.6146901 0.838820037,33.0841534 2.20054545,33.8792422 L16.7994545,42.4036834 C18.16118,43.1987722 19.83882,43.1987722 21.2005455,42.4036834 L35.7994545,33.8792422 C37.16118,33.0841534 38,31.6146901 38,30.0245126 L38,12.9755587 C38,11.3853099 37.16118,9.91591793 35.7994545,9.12075784 L21.2005455,0.596316588 C19.83882,-0.198772196 18.16118,-0.198772196 16.7994545,0.596316588 L2.20054545,9.12075784 C0.838820037,9.91591793 0,11.3853099 0,12.9755587 L0,30.0245126 Z"
                                                            fill="url(#titleLinearGradient-1)"></path>
                                                    </g>
                                                </svg>
                                            </div>
                                            <h4>Frequently Asked Questions</h4>
                                        </div>
                                        <p class="text use-text-subtitle2 text-md-start text-center">Have a question?
                                            Check out our frequently asked questions to find your answer.</p>
                                        <div class="hidden-sm-down">
                                            <div class="illustration">
                                                <img src="frontend/images/crypto/faq.png" alt="illustration"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 pa-6">
                                        <div class="accordion">
                                            <ul class="collapsible">
                                                <li class="accordion-content paper active">
                                                    <div class="collapsible-header content">
                                                        <p class="heading">Which Plan Is Right For Me?</p><i
                                                            class="material-icons right arrow">expand_more</i>
                                                    </div>
                                                    <div class="collapsible-body detail">
                                                        <p>With any financial product that you buy, it is important that you know you are getting the best advice from a reputable company as often.</p>
                                                    </div>
                                                </li>
                                                <li class="accordion-content paper">
                                                    <div class="collapsible-header content">
                                                        <p class="heading">How does the free trial work?</p><i
                                                            class="material-icons right arrow">expand_more</i>
                                                    </div>
                                                    <div class="collapsible-body detail">
                                                        <p>With any financial product that you buy, it is important that you know you are getting the best advice from a reputable company as often </p>
                                                    </div>
                                                </li>
                                                <li class="accordion-content paper">
                                                    <div class="collapsible-header content">
                                                        <p class="heading">What Payment Methods Are Available?</p><i
                                                            class="material-icons right arrow">expand_more</i>
                                                    </div>
                                                    <div class="collapsible-body detail">
                                                        <p>With any financial product that you buy, it is important that you know you are getting the best advice from a reputable company as often</p>
                                                    </div>
                                                </li>
                                                <li class="accordion-content paper">
                                                    <div class="collapsible-header content">
                                                        <p class="heading">What if I pick the wrong plan?</p><i
                                                            class="material-icons right arrow">expand_more</i>
                                                    </div>
                                                    <div class="collapsible-body detail">
                                                        <p>With any financial product that you buy, it is important that you know you are getting the best advice from a reputable company as often</p>
                                                    </div>
                                                </li>
                                                <li class="accordion-content paper">
                                                    <div class="collapsible-header content">
                                                        <p class="heading">If I have questions, where can I find answers?</p><i
                                                            class="material-icons right arrow">expand_more</i>
                                                    </div>
                                                    <div class="collapsible-body detail">
                                                        <p>With any financial product that you buy, it is important that you know you are getting the best advice from a reputable company as often </p>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section><!-- ##### END FAQ #####-->
                </main><!-- ##### FOOTER #####-->
                <div id="footer">
                    <div class="footer-counter">
                        <div class="deco-top back">
                            <svg>
                                <g stroke="none" stroke-width="1" fill-rule="evenodd">
                                    <path
                                        d="M1280,208.886425 L956.227132,80.8085307 C901.387269,59.1150077 840.691782,57.2610626 784.630132,75.5670961 L48.0014718,316.101381 C31.110148,321.616972 15.0479432,328.822426 -1.77635684e-15,337.512753 L0,0 L1280,0 L1280,208.886425 Z"></path>
                                </g>
                            </svg>
                        </div>
                        <div class="deco-top cover">
                            <svg>
                                <g stroke="none" stroke-width="1" fill-rule="evenodd">
                                    <path
                                        d="M1280,208.886425 L956.227132,80.8085307 C901.387269,59.1150077 840.691782,57.2610626 784.630132,75.5670961 L48.0014718,316.101381 C31.110148,321.616972 15.0479432,328.822426 -1.77635684e-15,337.512753 L0,0 L1280,0 L1280,208.886425 Z"></path>
                                </g>
                            </svg>
                        </div>
                        <div class="counter-wrap">
                            <div class="container max-md">
                                <h3 class="use-text-title"><span class="numscroller" data-min="0" data-max="2"
                                                                 data-increment="1"></span>&nbsp;<span
                                        class="numscroller" data-min="0" data-max="234" data-increment="5"></span>&nbsp;<span
                                        class="numscroller" data-min="0" data-max="567" data-increment="5"></span></h3>
                                <p class="use-text-subtitle">Users and Counting</p>
                                <div class="call-action">
                                    <h4 class="use-text-subtitle pb-2">What are you waiting for?</h4><a
                                        class="btn btn-large secondary button waves-effect" href="{{route('register')}}">get
                                        started</a>
                                </div>
                            </div>
                        </div>
                        <footer class="footer">
                            <div class="container fixed-width">
                                <div class="row spacing6">
                                    <div class="col-md-5 py-md-0 pa-6">
                                        <div class="logo">
                                            <img src="frontend/images/zzz-logo.png" alt="logo"/>

                                        </div>
                                        <div class="quick-links">
                                            <h6 class="title-nav mb-2">Quick Links</h6>
                                            <ul>
                                                <li><a href="{{route('privacy')}}">Privacy policy</a></li>
                                                <li><a href="{{route('contact')}}">Contact</a></li>
                                                <li><a href="{{route('tnc')}}">Terms Condition</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-5 py-md-0 py-6 px-8">
                                        <div class="socmed">
                                            <a class="btn-icon waves-effect"><span
                                                    class="ion-social-facebook icon"></span></a>
                                            <a class="btn-icon waves-effect"><span
                                                    class="ion-social-twitter icon"></span></a>
                                            <a class="btn-icon waves-effect"><span
                                                    class="ion-social-instagram icon"></span></a>
                                            <a class="btn-icon waves-effect"><span
                                                    class="ion-social-linkedin icon"></span></a>
                                        </div>

                                        <p class="body-2">&copy; All Right Reserved || ZZZ Securities</p>
                                    </div>
                                </div>
                            </div>
                        </footer>
                    </div>
                </div><!-- ##### END FOOTER #####-->
            </div><!-- ##### PAGE NAV #####-->
            <div class="hidden-md-down">
                <div class="page-nav" id="page_nav">
                    <nav class="section-nav">
                        <div class="scrollnav">
                            <ul>
                                <li style="top: 120px">
                                    <a class="tooltipped" href="#promotions" data-position="left"
                                       data-tooltip="promotions"></a>
                                </li>
                                <li style="top: 90px">
                                    <a class="tooltipped" href="#feature" data-position="left"
                                       data-tooltip="feature"></a>
                                </li>
                                <li style="top: 60px">
                                    <a class="tooltipped" href="#benefit" data-position="left"
                                       data-tooltip="benefit"></a>
                                </li>
                                <li style="top: 30px">
                                    <a class="tooltipped" href="#testimonials" data-position="left"
                                       data-tooltip="testimonials"></a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    <div class="scrollnav">
                        <a class="btn-floating btn-large primary tooltipped waves-effect waves-light" href="#home"
                           data-position="left" data-tooltip="To Top">
                            <i class="icon material-icons">arrow_upward</i>
                        </a>
                    </div>
                </div>
            </div><!-- ##### END PAGE NAV #####-->
            <!-- ##### NOTIFICATION #####-->
            <div class="hidden-md-down">
                <div class="alert full alert-dismissible fade show notification" role="alert">
                    <div class="wrapper">
                        <div class="content">
                            <div class="action">By clicking “Accept all cookies”, you agree Stack Exchange can store cookies on your device and disclose information in accordance with our Cookie Policy.
                            </div>
                            <button class="btn secondary waves-effect" type="button" data-dismiss="alert"
                                    aria-label="Close">Accept all cookies
                            </button>
                        </div>
                    </div>
                </div>
            </div><!-- ##### END NOTIFICATION #####-->
        </div>
        <script src="frontend/js/vendors/particles/particles.min.js"></script>
        <script src="frontend/js/vendors/particles/stats.min.js"></script>
    </div>
@endsection

