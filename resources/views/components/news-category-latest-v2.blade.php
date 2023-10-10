<!-- TITLE -->
<x-news-title
    title="Berita Terkini"
    href="/channels/Berita Terkini"
/>
<!-- CONTENT -->
<div class="row">
    @foreach ($data as $item)
    <div class="col-md-12 mb-30">
        <div class="row">
            <div class="col-6 col-md-4">
                <div class="lazy-container radius-md ratio ratio-7-3">
                        @if ($item->gambar != null )
                        <img class="lazyload" data-src="{{ asset('img/' . $item->gambar) }}"
                            alt="course" src="{{ asset('img/' . $item->gambar) }}" />
                        @else
                        <img class="lazyload" data-src="{{ asset('/img/rectangle-1603@2x.png') }}"
                            alt="course" src="{{ asset('/img/rectangle-1603@2x.png') }}" />
                        @endif
                </div>
            </div>
            <div class="col-6 col-md-8">
                <small>{{$item->channel_name}}</small>
                <h6 class="font-sm lh-base lc-3 my-2">{{$item->title}}</h6>
                <small>{{$item->created_at->locale('id')->diffForhumans()}}, {{$item->created_at->format(' H:i T') }}</small>
            </div>
        </div>
    </div>
    @endforeach
</div>