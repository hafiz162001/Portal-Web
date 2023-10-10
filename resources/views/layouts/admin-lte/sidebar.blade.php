<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
        <img src="{{ url('/admin-lte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        @guest
        @else
            {{-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ url('/admin-lte/dist/img/avatar.png') }}" class="img-circle elevation-2"
                        alt="User Image">
                </div>
                <div class="info">
                    <a href="{{ route('profile.index') }}" class="d-block">{{ Auth::user()->name }}</a>
                </div>
            </div> --}}
        @endguest

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @guest
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link {{ request()->is('login') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-sign-in-alt"></i>
                            <p>
                                Login
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="nav-link {{ request()->is('register*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-key"></i>
                            <p>
                                Register
                            </p>
                        </a>
                    </li>
                @else
                    @foreach (\App\Models\Navigation::with('menu', 'menu.submenu')->orderBy('order', 'asc')->get() as $nav)
                        <li class="app-sidebar__heading">{{ $nav->name }}</li>
                        @foreach ($nav->menu->where('status', true) as $menu)
                            @if ($menu->isHasMenu())
                                <li class="nav-item @if ($menu->getActive(Route::currentRouteName())) menu-is-opening menu-open @endif">
                                    <a href="#" class="nav-link @if ($menu->getActive(Route::currentRouteName())) active @endif">
                                        <i
                                            class="nav-icon @if ($menu->icon) {{ $menu->icon }} @endif"></i>
                                        <p>
                                            {{ $menu->name }}
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @foreach ($menu->submenu->where('status', true) as $submenu)
                                            @if ($submenu->isHasSubMenu())
                                                @php
                                                    $promoCodeCondition = str_contains(Route::currentRouteName(), 'promo') && str_contains($submenu->code, 'promo');
                                                    $evoriaArticleCommentCondition = str_contains(Route::currentRouteName(), 'evoria-article') && str_contains($submenu->code, 'evoria-article');
                                                    $evoriaForumThreadCondition = str_contains(Route::currentRouteName(), 'forum-thread') && str_contains($submenu->code, 'forum-thread');
                                                    // $evoriaCommunityThreadCondition = Route::currentRouteName() == 'evoria-community';
                                                    $evoriaCommunityThreadCondition = false;
                                                    if (str_contains(Route::currentRouteName(), 'evoria-community-thread')) {
                                                        $evoriaCommunityThreadCondition = $submenu->code == 'evoria-community';
                                                    }
                                                    $evoraContestanShowcaseComment = false;
                                                    if (str_contains(Route::currentRouteName(), 'evoria-contescase-comment')) {
                                                        $evoraContestanShowcaseComment = $submenu->code == 'contestan-show-case';
                                                    }
                                                @endphp
                                                <li class="nav-item">
                                                    <a href="@if ($submenu->path) {{ route($submenu->path) }} @else {{ '/' }} @endif"
                                                        class="nav-link @if ($submenu->getActive(Route::currentRouteName()) ||
                                                            $promoCodeCondition ||
                                                            $evoriaCommunityThreadCondition ||
                                                            $evoriaArticleCommentCondition ||
                                                            $evoriaForumThreadCondition ||
                                                            $evoraContestanShowcaseComment) active @endif">
                                                        <i class="far fa-circle nav-icon"></i>
                                                        <p>{{ $submenu->name }}</p>
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach
                    @endforeach
                @endguest
            </ul>
            {{-- @auth
                <ul class="profile-panel nav nav-pills nav-sidebar flex-column  mt-3 pb-3 pt-3 mb-3" data-widget="treeview"
                    role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ route('profile.index') }}"
                            class="nav-link {{ request()->is('profile*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                Profile
                            </p>
                        </a>
                    </li>
                </ul>
            @endauth --}}
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
