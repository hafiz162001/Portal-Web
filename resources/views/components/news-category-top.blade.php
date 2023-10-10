<div class="row">
    <div class="col-12">
        <!-- TITLE -->
        <x-news-title
            title="Top 10 Berita"
            href="/channels/Top 10 Berita"
        />
        <!-- CONTENT -->
        <div class="row">
            @foreach ($data as $i => $item)
            <div class="col-12 my-2">
                <a href="/detail/{{ $item->slug }}">
                    <div class="row">
                        <div class="col-1">
                            <h6 class="font-sm lh-base">
                                #{{ $i+1 }}
                            </h6>
                        </div>
                        <div class="col-11">
                            <h6 class="font-sm lh-base lc-3">
                                {{$item->title}}
                            </h6>
                            <small class="text-muted">{{$item->created_at->locale('id')->diffForhumans()}}, {{$item->created_at->format(' H:i T') }}</small>
                        </div>
                    </div>
                    <hr>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>