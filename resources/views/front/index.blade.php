@include('front.header')

<!-- Home-area start-->
<section class="hero-banner hero-banner_v1 header-next pt-0">
    <!-- <div class="container">
        <div id="carouselExampleDark" class="carousel carousel-dark slide">
            <div class="carousel-indicators">
                @foreach ($articles as $index => $item)
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="{{ $index }}"
                    class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                    aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach ($articles as $index => $item)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" data-bs-interval="3000">
                    <img src="{{ asset('img/' . $item->gambar) }}"
                        class="ratio ratio-2-1 radius-md" alt="...">

                    <div class="carousel-caption d-none d-md-block">
                        <h5 style="color: white;">
                            <a href="/detail/{{ $item->slug }}" style="color: white;">
                            {{ $item->title }}
                            </a>
                        </h5>
                        <p style="color: white;">{{ \Carbon\Carbon::parse($item->date)->format('d F Y H:i T') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div> -->
</section>

<!-- Course-area start -->
<!-- <section class="course-area d-none d-xl-block d-lg-block">
    <div class="container">
        <div class="col-12">
            <div class="row">
                @foreach ($articles as $index => $item)
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12
                    @if ($index == 0)
                        pe-0
                    @elseif (($index + 1) == count($articles))
                        ps-0
                    @else
                        px-0
                    @endif
                ">
                        <div onclick="moveToSlide({{$index}})">
                            @if ($item->gambar != null )
                            <img class="lazyload" data-src="{{ asset('img/' . $item->gambar) }}"
                                alt="course" src="{{ asset('img/' . $item->gambar) }}" />
                            @else
                            <img class="lazyload"
                                data-src="{{ asset('/img/rectangle-1603@2x.png') }}" alt="course"
                                src="{{ asset('/img/rectangle-1603@2x.png') }}" />
                            @endif
                        </div>
                        <div>
                            <a href="/detail/{{ $item->slug }}" target="_self" title="Channel"
                                class="font-sm mb-1">{{ $item->channel_name }}</a>
                            <a href="/detail/{{ $item->slug }}" target="_self" title="Detail">
                                <h6 class="font-sm lh-base lc-3 my-1 pe-1">
                                    {{ $item->title }}
                                </h6>
                                <p class="font-sm" style="color:#9CA3A5;">
                                    {{ $item->created_at->format('d M Y, H:i T') }}
                                </p>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section> -->
<!-- Mentor-area end -->

<x-carousel :data="$articles" />

<section class="course-area ptb-30">
    <div class="container">
        <div class="row">
            <!-- LEFT SECTION -->
            <div class="col-md-7">
                <!-- TITLE -->
                <x-news-title
                    title="Rekomendasi Untuk Anda"
                    href="/channels/Rekomendasi Untuk Anda"
                />
                <!-- CONTENT -->
                <div class="col-12">
                    <div class="row">
                        @foreach ($cardArticle9 as $item)
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mb-30">
                            <div class="row d-flex d-lg-block">
                                <div class="col-6 col-lg-12">
                                    <a href="/detail/{{ $item->slug }}" title="Image" target="_self"
                                        class="lazy-container rounded ratio ratio-2-3">
                                        @if ($item->gambar != null )
                                        <img class="lazyload" data-src="{{ asset('img/' . $item->gambar) }}"
                                            alt="course" src="{{ asset('img/' . $item->gambar) }}" />
                                        @else
                                        <img class="lazyload" data-src="{{ asset('/img/rectangle-1603@2x.png') }}"
                                            alt="course" src="{{ asset('/img/rectangle-1603@2x.png') }}" />
                                        @endif
                                    </a>
                                </div>
                                <div class="col-6 col-lg-12">
                                    <a href="/detail/{{ $item->slug }}" target="_self" title="Design"
                                        class="tag font-sm mb-1">
                                        {{ $item->channel_name }}
                                    </a>
                                    <a href="/detail/{{ $item->slug }}" target="_self" title="Link">
                                        <h6 class="font-sm lh-base lc-3 mb-0">
                                            <small>{{ $item->title }}</small>
                                        </h6>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- TITLE -->
                <x-news-title
                    title="Berita Terkini"
                    href="/channels/Berita Terkini"
                />
                <!-- CONTENT -->
                @foreach ($cardArticle8 as $item)
                <div class="col-12">
                    <!-- course-default -->
                    <div class="row g-0 course-column radius-md mb-25 align-items-center">
                        <div class="col-6 pe-2">
                            <div class="col-sm-12 col-xl-7 pe-xl-2">
                                <div>
                                    <a class="font-sm" href="/detail/{{ $item->slug }}" target="_self" title="Design">
                                        {{ $item->channel_name }}
                                    </a>
                                </div>
                                <div class="my-2">
                                    <a href="/detail/{{ $item->slug }}" target="_self" title="Link">
                                        <h6 class="font-sm lh-base lc-3 mb-0">
                                            {{ $item->title }}
                                        </h6>
                                    </a>
                                </div>
                                <span class="font-sm">
                                    {{ $item->created_at->locale('id')->diffForhumans() }}, {{ $item->created_at->format(' H:i T') }}
                                </span>
                            </div>
                        </div>

                        <div class="col-6">
                            <!-- komen hover effect -->
                            <!-- <figure class="course-img col-12"> -->
                                <a href="/detail/{{ $item->slug }}" title="Image" target="_self"
                                    class="lazy-container radius-md ratio ratio-7-3">
                                    @if ($item->gambar != null )
                                    <!-- Periksa apakah 'galleries' tidak kosong dan indeks 0 ada -->
                                    <img class="lazyload" data-src="{{ asset('img/' . $item->gambar) }}"
                                        alt="course" src="{{ asset('img/' . $item->gambar) }}" />
                                    @else
                                    <img class="lazyload" data-src="{{ asset('/img/rectangle-1603@2x.png') }}"
                                        alt="course" src="{{ asset('/img/rectangle-1603@2x.png') }}" />
                                    <!-- Gambar default jika tidak ada gambar di 'galleries' -->
                                    @endif
                                </a>
                            <!-- </figure> -->
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <!-- CENTER SECTION -->
            <!-- <div class="col-md-1"></div> -->
            <!-- RIGHT SECTION -->
            <div class="col-md-5">
                <!-- TOP 10 -->
                <x-news-category-top
                    :data="$cardArticle10"
                />

                <!-- ADS -->
                <div class="row">
                    <div class="col-12">
                        {{-- adsense --}}
                        <img src="https://thumbor.prod.vidiocdn.com/ejKN1Uo7yb4BhS6SkXfDSRHR6Sg=/filters:quality(70)/vidio-web-prod-film/uploads/film/image_portrait/3749/jujutsu-kaisen-79952b.jpg" alt="" srcset="">
                    </div>
                </div>

                <!-- TAGS -->
                <x-news-list-tag
                    :data="$tags"
                />
            </div>
        </div>
    </div>
</section>

<section class="course-area">
    <div class="container">
        <x-news-category-main
            :data="$cardArticle4"
        />
    </div>
</section>

<section class="course-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-7">
                        <x-news-category-latest-v2
                            :data="$cardArticle8"
                        />
                    </div>
                    <div class="col-md-5">
                        {{-- loop adsense in here --}}
                        <div class="row">
                            @for ($i = 0; $i < 1; $i++)
                            <div class="col-12">
                                <img src="https://thumbor.prod.vidiocdn.com/ejKN1Uo7yb4BhS6SkXfDSRHR6Sg=/filters:quality(70)/vidio-web-prod-film/uploads/film/image_portrait/3749/jujutsu-kaisen-79952b.jpg" alt="" srcset="">
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('front.footer')
