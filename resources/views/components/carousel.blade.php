<style>
.slick-dots li button:before,
[class*=" icon-"],
[class^="icon-"],
body {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale
}

.slick-slider {
    -webkit-tap-highlight-color: transparent
}

.slick-dots li button:focus,
.slick-dots li button:hover,
.slick-list:focus,
input:focus {
    outline: 0
}

.slick-track:after {
    clear: both
}

.slick-initialized .slick-slide,
.slick-slide img,
.social__block,
.topic__link,
.videos__link {
    display: block
}

.hl .slick-dots li.slick-active button,
.social--header .social__link:hover {
    background: #0bb1ef
}

.header__top .arrow.slick-disabled {
    opacity: .2
}

.hl .slick-dots li button {
    background: #fff
}

.slick-loading .slick-slide,
.slick-loading .slick-track {
    visibility: hidden
}

.hl .slick-dots {
    bottom: 1rem !important;
    text-align: right;
    padding-right: 1rem !important;
}

.hl .slick-dots li button:before {
    content: ""
}

.slick-slide.dragging img {
    pointer-events: none
}

.slick-arrow.slick-hidden,
.slick-slide.slick-loading img {
    display: none
}

.slick-dots li button:focus:before,
.slick-dots li button:hover:before {
    opacity: 1
}

.slick-slider {
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    -ms-touch-action: pan-y;
    touch-action: pan-y
}

.slick-list {
    overflow: hidden;
    margin: 0;
    padding: 0
}

.slick-list.dragging {
    cursor: pointer;
    cursor: hand
}

.slick-slider .slick-list,
.slick-slider .slick-track {
    -webkit-transform: translate3d(0, 0, 0);
    -moz-transform: translate3d(0, 0, 0);
    -ms-transform: translate3d(0, 0, 0);
    -o-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0)
}

.slick-track {
    left: 0;
    top: 0
}

.slick-track:after,
.slick-track:before {
    content: "";
    display: table
}

.slick-slide {
    float: left;
    outline: 0;
    height: 100%;
    min-height: 1px;
    display: none
}

.slick-vertical .slick-slide {
    display: block;
    height: auto;
    border: 1px solid transparent
}

.slick-dots {
    position: absolute;
    bottom: -45px;
    list-style: none;
    display: block;
    text-align: center;
    padding: 0;
    width: 100%
}

.slick-dots li {
    position: relative;
    display: inline-block;
    height: 10px;
    width: 10px;
    margin: 0 5px;
    padding: 0;
    cursor: pointer
}

.slick-dots li button {
    border: 0;
    background: 0 0;
    display: block;
    height: 10px;
    width: 10px;
    border-radius: 50%;
    outline: 0;
    line-height: 0;
    font-size: 0;
    color: transparent;
    padding: 5px;
    cursor: pointer
}

.slick-dots li button:before {
    position: absolute;
    top: 0;
    left: 0;
    content: "•";
    width: 10px;
    height: 10px;
    border-radius: 50%;
    font-size: 6px;
    line-height: 10px;
    text-align: center;
    color: #000;
    opacity: .25
}

.slick-dots li.slick-active button:before {
    color: #000;
    opacity: .75
}

.hl__b-box {
    width: 100%;
    position: absolute;
    bottom: 0;
    left: 0;
    background: linear-gradient(90deg, #fff, transparent);
    background: -webkit-linear-gradient(90deg, #000, transparent)
}
</style>

<section class="hl">
    <div class="container">
        <div class="js--big" style="position: relative;">
            @foreach ($data as $item)
            <div style="position: relative;">
                <div class="ratio ratio-16-9">
                    <img
                        src="{{ asset('img/' . $item->gambar) }}"
                        alt="{{ $item->title }}"
                    >
                </div>
                <div class="hl__b-box">
                    <div class="container">
                        <div class="col-12 mb-2">
                            <h6 style="color:#fff; display: inline; border-bottom: 1px solid #fff; letter-spacing: 2.4px;">
                                {{ $item->channel_name }}
                            </h6>
                            <h6 class="font-md lh-base lc-3 pt-2" style="color:#fff;">
                                {{ $item->title }}
                            </h6>
                            <small style="color:#fff;">
                                {{ $item->created_at->locale('id')->diffForhumans()}}, {{$item->created_at->format(' H:i T') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="js--small d-none d-lg-block">
            @foreach ($data as $item)
            <div>
                <div>
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
</section>