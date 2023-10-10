<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Muhlis">
    <meta name="description" content="News">

    <!-- Title -->
    <title>Beritaku</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/fav.png') }}" type="image/x-icon">

    <!-- Google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/bootstrap.min.css')}}">
    <!-- Fontawesome Icon CSS -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome/css/all.min.css')}}">
    <!-- Icomoon Icon CSS -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/icomoon/style.css')}}">
    <!-- Magnific Popup CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/magnific-popup.min.css')}}">
    <!-- NoUi Range Slider -->
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/nouislider.min.css')}}">
    <!-- Swiper Slider -->
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/swiper-bundle.min.css')}}">
    <!-- Nice Select -->
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/nice-select.css')}}">
    <!-- AOS Animation CSS -->
    <!-- <link rel="stylesheet" href="{{ asset('assets/css/vendors/aos.min.css')}}"> -->
    <!-- Animate CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/animate.min.css')}}">
    <!-- Main Style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css')}}">
    <!-- Responsive CSS -->
    <!-- <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}"> -->

    <style>
        .baca-juga{
            background-color: #C6DEFA;
            border-radius: 8px;
            padding: 10px;
            font-weight: bold;
            color:black
        }
    </style>
</head>

<body class="theme-color-1">
    <!-- <header class="header-area d-block d-lg-none">
        <div class="mobile-menu">
            <div class="container">
                <div class="mobile-menu-wrapper"></div>
            </div>
        </div>

        <div class="main-responsive-nav">
            <div class="container">
                <div class="logo">
                    <a href="index.html" target="_self" title="Oppida">
                        <img class="lazyload" src="{{ asset('assets/images/placeholder.png') }}" style="100px" data-src="{{ asset('assets/public/beritaku-1@2x.png') }}" alt="Brand Logo">
                    </a>
                </div>
                <button class="menu-toggler" type="button">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </header> -->

    <!-- DESKTOP -->
    <div class="d-none d-lg-block">
        <div class="header-top bg-white pt-3 pb-4 mobile-item border-bottom">
            <div class="container">
                <div class="d-flex flex-wrap justify-content-between gap-15 align-items-center">
                    <a href="mailTo:examle@email.com" class="icon-start" target="_self" title="example@email.com">
                        <i class="fal fa-globe"></i>
                        Terhubung
                    </a>
                    <div class="social-link style-2 size-md">
                        <a class="rounded-pill" href="https://www.instagram.com/" target="_blank" title="instagram"><i class="fab fa-instagram"></i></a>
                        <a class="rounded-pill" href="https://www.dribbble.com/" target="_blank" title="dribbble"><i class="fab fa-dribbble"></i></a>
                        <a class="rounded-pill" href="https://www.twitter.com/" target="_blank" title="twitter"><i class="fab fa-twitter"></i></a>
                        <a class="rounded-pill" href="https://www.youtube.com/" target="_blank" title="youtube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-none d-lg-block">
        <div class="header-top bg-white py-1">
            <section class="newsletter-area newsletter-area_v1">
                <div class="container">
                    <div class="newsletter-inner ptb-60 px-3 px-lg-5 radius-lg bg-img bg-cover" data-bg-image="{{ asset('assets/public/frame-2551@2x.png') }}">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="content-title">
                                    <h2 class="title mb-30" style="visibility: hidden;">
                                        Subscribe To Our
                                        Newsletter
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <div class="d-none d-lg-block">
        <div class="header-top bg-white py-3 mobile-item border-bottom">
            <div class="container">
                <div class="d-flex flex-wrap justify-content-between gap-15 align-items-center">
                    <a class="navbar-brand" href="{{ url('/') }}" target="_self" title="Oppida">
                        <img class="lazyload" src="{{ asset('assets/images/placeholder.png') }}" data-src="{{ asset('assets/public/beritaku-1@2x.png') }}" alt="Brand Logo"style="width:260px">
                    </a>
                    <div class="social-link style-2 size-md">
                        <div class="input-group">
                            <form action="/front">
                                <label for="search"></label>
                                <input type="text" name="search" value="{{ request('search') }}" id="search" class="form-control" placeholder="Cari Berita">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar sticky-top d-none d-lg-block" style="background: #072B55;">
        <div class="container">
            <div class="header-bottom">
                <nav class="navbar navbar-expand-lg">
                    <div class="collapse navbar-collapse">
                        <ul id="mainMenu" class="navbar-nav mobile-item mx-auto">
                            @foreach ($channel9 as $i => $item)
                            <li class="nav-item">
                                <a
                                    href="/channels/{{ $item->name }}"
                                    class="nav-link toggle
                                    @if ($i == 0) ps-0
                                    @endif
                                    "
                                    style="color:#fff;"
                                >{{ $item['name'] }}</i></a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </nav>

    <!-- MOBILE -->
    <nav class="navbar sticky-top d-lg-none d-block">
        <!-- DIVIDER -->
        <div class="navbar navbar-expand-xl">
            <br>
        </div>

        <!-- NAV -->
        <div class="header-area" style="background-color:#fff;">
            <div class="mobile-menu">
                <div class="container">
                    <div class="mobile-menu-wrapper"></div>
                </div>
            </div>

            <div class="main-responsive-nav">
                <div class="container">
                    <div class="logo">
                        <a href="index.html" target="_self" title="Oppida">
                            <img class="lazyload" src="{{ asset('assets/images/placeholder.png') }}" style="100px" data-src="{{ asset('assets/public/beritaku-1@2x.png') }}" alt="Brand Logo">
                        </a>
                    </div>
                    <button class="menu-toggler" type="button">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
            <div class="main-navbar">
                <div class="header-top bg-white py-3 mobile-item border-bottom">
                    <div class="container">
                        <div class="d-flex flex-wrap justify-content-between gap-15 align-items-center">
                            <a href="mailTo:examle@email.com" class="icon-start" target="_self" title="example@email.com">
                                <i class="fal fa-globe"></i>
                               Terhubung
                            </a>
                            <div class="social-link style-2 size-md">
                                <a class="rounded-pill" href="https://www.instagram.com/" target="_blank" title="instagram"><i class="fab fa-instagram"></i></a>
                                <a class="rounded-pill" href="https://www.dribbble.com/" target="_blank" title="dribbble"><i class="fab fa-dribbble"></i></a>
                                <a class="rounded-pill" href="https://www.twitter.com/" target="_blank" title="twitter"><i class="fab fa-twitter"></i></a>
                                <a class="rounded-pill" href="https://www.youtube.com/" target="_blank" title="youtube"><i class="fab fa-youtube"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                  <!-- Newsletter-area start -->
                  <div class="main-navbar">
                    <div class="header-top bg-white py-3">
                  <section class="newsletter-area newsletter-area_v1">
                    <div class="container">
                        <div class="newsletter-inner ptb-60 px-3 px-lg-5 radius-lg bg-img bg-cover" data-bg-image="assets/public/frame-2551@2x.png" data-aos="fade-up">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="content-title">
                                        <h2 class="title mb-30"style="visibility: hidden;">
                                            Subscribe To Our
                                            Newsletter
                                        </h2>

                                        </div>
                                    </div>
                                </div>
                                </div>

                            </div>

                    </div>
            </div>
                </section>
        <!-- Newsletter-area end -->
                 <div class="main-navbar">

                <div class="header-top bg-white py-3 mobile-item border-bottom">
                    <div class="container">
                        <div class="d-flex flex-wrap justify-content-between gap-15 align-items-center">
                            <a class="navbar-brand" href="index.html" target="_self" title="Oppida">
                                <img class="lazyload" src="{{ asset('assets/images/placeholder.png') }}" style="100px" data-src="{{ asset('assets/public/beritaku-1@2x.png') }}" alt="Brand Logo">
                            </a>
                            <div class="social-link style-2 size-md">

                                                    <div class="input-group">
                                                        <label for="search"></label>
                                                        <input type="text" id="search" class="form-control" placeholder="Cari Berita">
                                                        <div class="vr"></div>
                                                    </div>
                                                </div></div>
                        </div>
                    </div>
                </div>

            <!-- Newsletter-area end -->

                <div class="header-bottom"style="background:#072B55">
                    <div class="container">
                        <nav class="navbar navbar-expand-lg">
                            <!-- Logo -->

                            <!-- Navigation items -->
                            <div class="collapse navbar-collapse">
                                <ul id="mainMenu" class="navbar-nav mobile-item mx-auto">
                                    @foreach ($channel9 as $i => $item)
                                    <li class="nav-item">
                                        <a
                                            href="/channels/{{ $item->name }}"
                                            class="nav-link toggle
                                            @if ($i == 0) ps-0
                                            @endif
                                            "
                                            style="color:black;"
                                        >{{ $item['name'] }}</i></a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </nav>

                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </header>
        <!-- Header-area end -->

        </div>
    </nav>
