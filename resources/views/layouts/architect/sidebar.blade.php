<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            @foreach ( \App\Models\Navigation::with('menu', 'menu.submenu')->orderBy('order','asc')->get() as $nav)
                <ul class="vertical-nav-menu">
                    <li class="app-sidebar__heading">{{ $nav->name }}</li>
                    @foreach ($nav->menu->where('status', true) as $menu)
                        @if($menu->isHasMenu())
                            <li class="@if ($menu->getActive(Route::currentRouteName())) {{ 'mm-active' }} @endif">
                                <a href="#">
                                    <i class="@if ($menu->icon) {{ 'fa ' . $menu->icon }} @endif metismenu-icon pe-7s-rocket"></i>
                                    {{ $menu->name }}
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul>
                                    @foreach ($menu->submenu->where('status', true) as $submenu)
                                        @if($submenu->isHasSubMenu())
                                            <li>
                                                <a href="@if($submenu->path) {{ route($submenu->path) }} @else {{ '/' }} @endif" class="@if ($submenu->getActive( Route::currentRouteName())) {{ 'mm-active' }} @endif">
                                                    <i class="metismenu-icon">
                                                    </i>{{ $submenu->name }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endforeach
                </ul>
            @endforeach
        </div>
    </div>
</div>
