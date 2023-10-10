<div class="row justify-content-between my-2">
    <div class="col-7">
        <h5 class="text-left mt-1">{{ $title }}</h5>
    </div>
    @if ($href)
    <div class="col-auto">
        <div class="tabs-navigation tabs-navigation_gradient">
            <!-- data-hover="fancyHover" -->
            <ul class="nav nav-tabs"> 
                <li class="nav-item">
                    <a href="{{ $href }}" class="nav-link btn-md" style="color:#072B55;font-weight:700;">
                        Lihat Semua
                    </a>
                </li>
            </ul>
        </div>
    </div>
    @endif
</div>