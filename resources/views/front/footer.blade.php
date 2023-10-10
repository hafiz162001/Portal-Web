<!-- Footer-area start -->
<footer class="bg-primary-light">
    <div class="footer-top pt-40 pb-60">
        <div class="container">
            <div class="row gx-xl-5 justify-content-between">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="footer-widget">
                        <div class="logo mb-20">
                            <a class="navbar-brand" href="index.html" target="_self" title="Link">
                                <img class="lazyload" src="{{ asset('assets/public/beritaku-1@2x.png') }}" data-src="{{ asset('assets/public/beritaku-1@2x.png') }}" alt="Brand Logo" style="width:260px">
                            </a>
                        </div>
                        <small class="text-sm-center">Kanal berita nomer satu di Indonesia</small>
                    </div>

                    <div class="pt-20 mb-30">
                        <small class="text-sm-center">Tetap terhubung bersama kami</small>
                        <div class="social-link rounded style-2 justify-content-start mt-10">
                            <a href="https://www.instagram.com/" target="_blank" title="Link"><i class="fab fa-instagram"></i></a>
                            <a href="https://www.dribbble.com/" target="_blank" title="Link"><i class="fab fa-dribbble"></i></a>
                            <a href="https://www.twitter.com/" target="_blank" title="Link"><i class="fab fa-twitter"></i></a>
                            <a href="https://www.youtube.com/" target="_blank" title="Link"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="d-flex justify-content-space-between">
                        <div class="col-4 inline clearfix">
                            @foreach ($channels->skip(6*0) as $key => $item)
                                @if ($key > (5 + 0))
                                @else
                                <a href="/channel/{{ $item['name'] }}" target="_self" title="link">
                                    <h6 class="font-sm">{{ $item['name'] }}</h6>
                                </a>
                                @endif
                            @endforeach
                        </div>

                        <div class="col-4 inline clearfix">
                            @foreach ($channels->skip(6*1) as $key => $item)
                                @if ($key > (5 + 6))
                                    @else
                                    <a href="/channel/{{ $item['name'] }}" target="_self" title="link">
                                        <h6 class="font-sm">{{ $item['name'] }}</h6>
                                    </a>
                                @endif
                            @endforeach
                        </div>

                        <div class="col-4 inline clearfix">
                            @foreach ($channels->skip(6*2) as $key => $item)
                                @if ($key > (5 + 12))
                                    @else
                                    <a href="/channel/{{ $item['name'] }}" target="_self" title="link">
                                        <h6 class="font-sm">{{ $item['name'] }}</h6>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copy-right-area">
        <div class="container">
            <div class="copy-right-content">
                <div class="d-flex justify-content-center align-items-center mb-2">
                    <span>
                        <span id="footerDate"></span>
                        <i class="fal fa-copyright"></i>
                        <a href="#" target="_self" title="Oppida" class="color-">beritaku.com</a> All Rights Reserved
                    </span>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer-area end-->

<!-- Jquery JS -->
<script src="{{ asset('assets/js/vendors/jquery.min.js') }}"></script>
<!-- Bootstrap JS -->
<script src="{{ asset('assets/js/vendors/bootstrap.min.js') }}"></script>
<!-- Counter JS -->
<script src="{{ asset('assets/js/vendors/jquery.counterup.min.js') }}"></script>
<!-- Nice Select JS -->
<script src="{{ asset('assets/js/vendors/jquery.nice-select.min.js') }}"></script>
<!-- Magnific Popup JS -->
<script src="{{ asset('assets/js/vendors/jquery.magnific-popup.min.js') }}"></script>
<!-- Noui Range Slider JS -->
<script src="{{ asset('assets/js/vendors/nouislider.min.js') }}"></script>
<!-- Swiper Slider JS -->
<script src="{{ asset('assets/js/vendors/swiper-bundle.min.js') }}"></script>
<!-- Lazysizes -->
<script src="{{ asset('assets/js/vendors/lazysizes.min.js') }}"></script>
<!-- AOS JS -->
<!-- <script src="{{ asset('assets/js/vendors/aos.min.js') }}"></script> -->
<!-- Mouse Hover JS -->
<script src="{{ asset('assets/js/vendors/mouse-hover-move.js') }}"></script>
<!-- Main script JS -->
<script src="{{ asset('assets/js/script.js') }}"></script>
<!--  -->
<script src="{{ asset('assets/js/slick.min.js') }}"></script>
    <!--  -->
<script src="{{ asset('assets/js/main.js') }}"></script>
<!--  -->
<script>
      function moveToSlide(slideIndex) {
    $("#carouselExampleDark").carousel(slideIndex);
  }
</script>
</body>

</html>
