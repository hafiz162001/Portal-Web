@include('front.header')
@if ($channel->count())

<section class="course-area latest ptb d-none d-md-block">
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
</section>
<section class="course-area pb-100 d-block d-sm-none">
    <br>

</section>
<section class="course-area latest ptb-100 d-none d-sm-block">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title title-inline mb-30" data-aos="fade-up">
                    <h2 class="title mb-10">
                        Hasil
                        {{-- <img class="title-shape lazyload" src="assets/images/placeholder.png" data-src="assets/images/shape/title-shape.png" alt="Shape"> --}}
                    </h2>
                    <div class="tabs-navigation mb-20">

                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="tab-content" data-aos="fade-up">
                    <div class="tab-pane slide show active" id="tab1">
                        <div class="row">
                           @foreach ($channel as $item)
                           <div class="col-xl-3 col-lg-4 col-sm-6">
                            <div class="course-default border radius-md mb-30">
                                <figure class="course-img">
                                    <a href="/detail/{{ $item['slug'] }}" title="Image" target="_self" class="lazy-container ratio ratio-2-3">
                                        @if ($item['gambar'] != null ) <!-- Periksa apakah 'galleries' tidak kosong dan indeks 0 ada -->
                                            <img class="lazyload" data-src="{{ asset('img/' . $item['gambar']) }}" alt="course" src="{{ asset('img/' . $item['gambar']) }}" />
                                            @else
                                                <img class="lazyload" data-src="{{ asset('/img/rectangle-1603@2x.png') }}" alt="course" src="{{ asset('/img/rectangle-1603@2x.png') }}" /> <!-- Gambar default jika tidak ada gambar di 'galleries' -->
                                            @endif
                                    </a>
                                    {{-- <div class="hover-show">
                                        <a href="/detail/{{ $item['slug'] }}" class="btn btn-md btn- rounded-pill" title="Enroll Now" target="_self">Enroll Now</a>
                                    </div> --}}
                                </figure>
                                <div class="course-details">
                                    <div class="p-3">
                                        <a href="course-details.html" target="_self" title="Design" class="tag font-sm color- mb-1">{{ $item['category_name'] }}</a>
                                        <h6 class="course-title lc-2 mb-0">
                                            <a href="/detail/{{ $item['slug'] }}" target="_self" title="Link">
                                               {{ $item['title'] }}
                                            </a>
                                        </h6>
                                        <div class="authors mt-15">
                                            <div class="author">
                                            <span class="font-sm">
                                                    <a href="course-details.html" target="_self" title="James Hobert">
                                                    Hari ini, 10:20 Wib
                                                    </a>
                                                </span>
                                            </div>
                                     </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                           @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="course-area pb-100 d-block d-sm-none">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title title-inline mb-30" data-aos="fade-up">
                    <h2 class="title mb-20">Hasil </h2>
                    <div class="tabs-navigation tabs-navigation_gradient mb-20">

                    </div>
                </div>
            </div>
            @foreach ($channel as $item)
            <div class="col-12">
                <div class="tab-content" data-aos="fade-up">
                    <div class="tab-pane slide show active" id="tab1">
                        <div class="row">
                            <div class="col-xl-6 col-lg-4 col-sm-6">
                                <div class="row g-0 course-default course-column border radius-md mb-25 align-items-center">
                                    <figure class="course-img col-sm-12 col-xl-5">
                                        <a href="/detail/{{ $item['slug'] }}" title="Image" target="_self" class="lazy-container radius-md ratio ratio-5-4">

                                            @if ($item['gambar'] != null ) <!-- Periksa apakah 'galleries' tidak kosong dan indeks 0 ada -->
                                            <img class="lazyload" data-src="{{ asset('img/' . $item['gambar']) }}" alt="course" src="{{ asset('img/' . $item['gambar']) }}" />
                                            @else
                                            <img class="lazyload" src="assets/images/placeholder.png" data-src="assets/images/course/pro-29.jpg" alt="course"> <!-- Gambar default jika tidak ada gambar di 'galleries' -->
                                            @endif
                                        </a>
                                        {{-- <div class="hover-show radius-md">
                                            <a href="course-details.html" class="btn btn-md btn- btn-gradient rounded-pill" title="Enroll Now" target="_self">Enroll Now</a>
                                        </div> --}}
                                    </figure>
                                    <div class="course-details col-sm-12 col-xl-7 ps-xl-2">
                                        <div class="p-3">
                                            <a href="/detail/{{ $item['slug'] }}" target="_self" title="Design">{{ $item['category_name'] }}</a>
                                            <h6 class="course-title lc-2 mb-0">
                                                <a href="/detail/{{ $item['slug'] }}" target="_self" title="Link">
                                               {{ $item['title'] }}
                                                </a>
                                            </h6>
                                            <div class="authors mt-15">
                                                <div class="author">
                                                  <span class="font-sm">
                                                        <a href="/detail/{{ $item['slug'] }}" target="_self" title="/detail/{{ $item['slug'] }}">
                                                        <p style="color:white"> {{ $item['editor'] }}</p>
                                                        </a>
                                                    </span>
                                                </div>
                                           </div>
                                        </div>
                                        <div class="px-xl-3 mb-1">
                                            <div class="course-bottom-info py-2 px-3 px-xl-0">
                                                <span class="font-sm">Hari ini,   {{ $item->created_at->format('d F Y') }}</span>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@else
<br>
<br>
<br>
<br>
<br>
<h2 class="title mb-20" style="align-content: center">Tidak ada Data</h2>
@endif

<div class="d-flex justify-content-center mt-3">
    {{-- {{ $channel->links() }} --}}
</div>
@include('front.footer')
