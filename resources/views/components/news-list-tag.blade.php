<div class="row">
    <div class="col-12">
        <!-- TITLE -->
        <x-news-title
            title="Tag Terpopuler"
            href=""
        />
        <!-- CONTENT -->
        <!-- <div class="row">
            @foreach ($data as $item)
            <div class="col-auto my-2">
                <a class="rounded-pill border border-dark p-2">
                    {{$item->name}}
                </a>
            </div>
            @endforeach
        </div> -->
        <div class="mt-2">
            <div class="d-flex flex-wrap gap-2">
                @foreach ($data as $item)
                <a
                    href="/channels/{{ $item->id }}"
                    class="p-3 me-2 mb-2 bg-light rounded-pill border border-dark font-xsm"
                    title="Tag"
                    target="_self"
                >
                    {{ $item->name }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>