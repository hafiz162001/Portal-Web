<div class="row">
    <div class="col-12">
        <!-- TITLE -->
        <x-news-title
            title="Berita Utama"
            href="/channels/Berita Utama"
        />
        <!-- CONTENT -->
        <div class="row">
            @foreach ($data as $i => $item)
            <div class="col-md-6 col-lg-3 mb-30">
                <div class="row d-flex d-lg-block">
                    <div class="col-6 col-lg-12">
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
                    <div class="col-6 col-lg-12">
                        <div class="card-body px-0">
                            <small>{{$item->channel_name}}</small>
                            <h6 class="font-sm lh-base lc-3 my-2">{{$item->title}}</h6>
                            <small>{{$item->created_at->locale('id')->diffForhumans()}}, {{$item->created_at->format(' H:i T') }}</small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>