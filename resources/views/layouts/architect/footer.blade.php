<div class="app-wrapper-footer">
    <div class="app-footer">
        <div class="app-footer__inner">
            <div class="app-footer-left">
                <div class="footer-dots">
                    <span>@ BankData 2021</span>
                    {{-- <div class="dropdown">
                        <a aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dot-btn-wrapper">
                            <i class="dot-btn-icon lnr-bullhorn icon-gradient bg-mean-fruit"></i>
                            <div class="badge badge-dot badge-abs badge-dot-sm badge-danger">
                                Notifications</div>
                        </a>
                        <div tabindex="-1" role="menu" aria-hidden="true"
                            class="dropdown-menu-xl rm-pointers dropdown-menu">
                            <div class="dropdown-menu-header mb-0">
                                <div class="dropdown-menu-header-inner bg-deep-blue">
                                    <div class="menu-header-image opacity-1"
                                        style="background-image: url('{{ asset('architect/images/dropdown-header/city3.jpg') }}');">
                                    </div>
                                    <div class="menu-header-content text-dark">
                                        <h5 class="menu-header-title">Notifications</h5>
                                        <h6 class="menu-header-subtitle">You have <b>21</b> unread
                                            messages</h6>
                                    </div>
                                </div>
                            </div>
                            <ul class="tabs-animated-shadow tabs-animated nav nav-justified tabs-shadow-bordered p-3">
                                <li class="nav-item">
                                    <a role="tab" class="nav-link active" data-toggle="tab"
                                        href="#tab-messages-header1">
                                        <span>Messages</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" class="nav-link" data-toggle="tab" href="#tab-events-header1">
                                        <span>Events</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" class="nav-link" data-toggle="tab" href="#tab-errors-header1">
                                        <span>System Errors</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab-messages-header1" role="tabpanel">
                                    <div class="scroll-area-sm">
                                        <div class="scrollbar-container">
                                            <div class="p-3">
                                                <div class="notifications-box">
                                                    <div
                                                        class="vertical-time-simple vertical-without-time vertical-timeline vertical-timeline--one-column">
                                                        <div
                                                            class="vertical-timeline-item dot-danger vertical-timeline-element">
                                                            <div><span
                                                                    class="vertical-timeline-element-icon bounce-in"></span>
                                                                <div
                                                                    class="vertical-timeline-element-content bounce-in">
                                                                    <h4 class="timeline-title">All
                                                                        Hands Meeting</h4><span
                                                                        class="vertical-timeline-element-date"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="vertical-timeline-item dot-warning vertical-timeline-element">
                                                            <div><span
                                                                    class="vertical-timeline-element-icon bounce-in"></span>
                                                                <div
                                                                    class="vertical-timeline-element-content bounce-in">
                                                                    <p>Yet another one, at <span
                                                                            class="text-success">15:00
                                                                            PM</span></p><span
                                                                        class="vertical-timeline-element-date"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="vertical-timeline-item dot-success vertical-timeline-element">
                                                            <div><span
                                                                    class="vertical-timeline-element-icon bounce-in"></span>
                                                                <div
                                                                    class="vertical-timeline-element-content bounce-in">
                                                                    <h4 class="timeline-title">Build
                                                                        the production release
                                                                        <span class="badge badge-danger ml-2">NEW</span>
                                                                    </h4>
                                                                    <span class="vertical-timeline-element-date"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="vertical-timeline-item dot-primary vertical-timeline-element">
                                                            <div><span
                                                                    class="vertical-timeline-element-icon bounce-in"></span>
                                                                <div
                                                                    class="vertical-timeline-element-content bounce-in">
                                                                    <h4 class="timeline-title">
                                                                        Something not important
                                                                        <div
                                                                            class="avatar-wrapper mt-2 avatar-wrapper-overlap">
                                                                            <div
                                                                                class="avatar-icon-wrapper avatar-icon-sm">
                                                                                <div class="avatar-icon">
                                                                                    <img src="{{ asset('architect/images/avatars/1.jpg') }}"
                                                                                        alt="">
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="avatar-icon-wrapper avatar-icon-sm">
                                                                                <div class="avatar-icon">
                                                                                    <img src="{{ asset('architect/images/avatars/2.jpg') }}"
                                                                                        alt="">
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="avatar-icon-wrapper avatar-icon-sm">
                                                                                <div class="avatar-icon">
                                                                                    <img src="{{ asset('architect/images/avatars/3.jpg') }}"
                                                                                        alt="">
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="avatar-icon-wrapper avatar-icon-sm">
                                                                                <div class="avatar-icon">
                                                                                    <img src="{{ asset('architect/images/avatars/4.jpg') }}"
                                                                                        alt="">
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="avatar-icon-wrapper avatar-icon-sm">
                                                                                <div class="avatar-icon">
                                                                                    <img src="{{ asset('architect/images/avatars/5.jpg') }}"
                                                                                        alt="">
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="avatar-icon-wrapper avatar-icon-sm">
                                                                                <div class="avatar-icon">
                                                                                    <img src="{{ asset('architect/images/avatars/9.jpg') }}"
                                                                                        alt="">
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="avatar-icon-wrapper avatar-icon-sm">
                                                                                <div class="avatar-icon">
                                                                                    <img src="{{ asset('architect/images/avatars/7.jpg') }}"
                                                                                        alt="">
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="avatar-icon-wrapper avatar-icon-sm">
                                                                                <div class="avatar-icon">
                                                                                    <img src="{{ asset('architect/images/avatars/8.jpg') }}"
                                                                                        alt="">
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="avatar-icon-wrapper avatar-icon-sm avatar-icon-add">
                                                                                <div class="avatar-icon">
                                                                                    <i>+</i>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </h4>
                                                                    <span class="vertical-timeline-element-date"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="vertical-timeline-item dot-info vertical-timeline-element">
                                                            <div><span
                                                                    class="vertical-timeline-element-icon bounce-in"></span>
                                                                <div
                                                                    class="vertical-timeline-element-content bounce-in">
                                                                    <h4 class="timeline-title">This
                                                                        dot has an info state</h4><span
                                                                        class="vertical-timeline-element-date"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="vertical-timeline-item dot-danger vertical-timeline-element">
                                                            <div><span
                                                                    class="vertical-timeline-element-icon bounce-in"></span>
                                                                <div
                                                                    class="vertical-timeline-element-content bounce-in">
                                                                    <h4 class="timeline-title">All
                                                                        Hands Meeting</h4><span
                                                                        class="vertical-timeline-element-date"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="vertical-timeline-item dot-warning vertical-timeline-element">
                                                            <div><span
                                                                    class="vertical-timeline-element-icon bounce-in"></span>
                                                                <div
                                                                    class="vertical-timeline-element-content bounce-in">
                                                                    <p>Yet another one, at <span
                                                                            class="text-success">15:00
                                                                            PM</span></p><span
                                                                        class="vertical-timeline-element-date"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="vertical-timeline-item dot-success vertical-timeline-element">
                                                            <div><span
                                                                    class="vertical-timeline-element-icon bounce-in"></span>
                                                                <div
                                                                    class="vertical-timeline-element-content bounce-in">
                                                                    <h4 class="timeline-title">Build
                                                                        the production release
                                                                        <span class="badge badge-danger ml-2">NEW</span>
                                                                    </h4>
                                                                    <span class="vertical-timeline-element-date"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="vertical-timeline-item dot-dark vertical-timeline-element">
                                                            <div><span
                                                                    class="vertical-timeline-element-icon bounce-in"></span>
                                                                <div
                                                                    class="vertical-timeline-element-content bounce-in">
                                                                    <h4 class="timeline-title">This
                                                                        dot has a dark state</h4><span
                                                                        class="vertical-timeline-element-date"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab-events-header1" role="tabpanel">
                                    <div class="scroll-area-sm">
                                        <div class="scrollbar-container">
                                            <div class="p-3">
                                                <div
                                                    class="vertical-without-time vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                        <div><span class="vertical-timeline-element-icon bounce-in"><i
                                                                    class="badge badge-dot badge-dot-xl badge-success">
                                                                </i></span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title">All Hands
                                                                    Meeting</h4>
                                                                <p>Lorem ipsum dolor sic amet, today at
                                                                    <a href="javascript:void(0);">12:00
                                                                        PM</a>
                                                                </p><span
                                                                    class="vertical-timeline-element-date"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                        <div><span class="vertical-timeline-element-icon bounce-in"><i
                                                                    class="badge badge-dot badge-dot-xl badge-warning">
                                                                </i></span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <p>Another meeting today, at <b
                                                                        class="text-danger">12:00
                                                                        PM</b></p>
                                                                <p>Yet another one, at <span
                                                                        class="text-success">15:00
                                                                        PM</span></p><span
                                                                    class="vertical-timeline-element-date"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                        <div><span class="vertical-timeline-element-icon bounce-in"><i
                                                                    class="badge badge-dot badge-dot-xl badge-danger">
                                                                </i></span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title">Build the
                                                                    production release</h4>
                                                                <p>Lorem ipsum dolor sit
                                                                    amit,consectetur eiusmdd tempor
                                                                    incididunt ut labore et dolore magna
                                                                    elit enim at minim veniam quis
                                                                    nostrud</p><span
                                                                    class="vertical-timeline-element-date"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                        <div><span class="vertical-timeline-element-icon bounce-in"><i
                                                                    class="badge badge-dot badge-dot-xl badge-primary">
                                                                </i></span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-success">
                                                                    Something not important</h4>
                                                                <p>Lorem ipsum dolor sit
                                                                    amit,consectetur elit enim at minim
                                                                    veniam quis nostrud</p><span
                                                                    class="vertical-timeline-element-date"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                        <div><span class="vertical-timeline-element-icon bounce-in"><i
                                                                    class="badge badge-dot badge-dot-xl badge-success">
                                                                </i></span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title">All Hands
                                                                    Meeting</h4>
                                                                <p>Lorem ipsum dolor sic amet, today at
                                                                    <a href="javascript:void(0);">12:00
                                                                        PM</a>
                                                                </p><span
                                                                    class="vertical-timeline-element-date"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                        <div><span class="vertical-timeline-element-icon bounce-in"><i
                                                                    class="badge badge-dot badge-dot-xl badge-warning">
                                                                </i></span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <p>Another meeting today, at <b
                                                                        class="text-danger">12:00
                                                                        PM</b></p>
                                                                <p>Yet another one, at <span
                                                                        class="text-success">15:00
                                                                        PM</span></p><span
                                                                    class="vertical-timeline-element-date"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                        <div><span class="vertical-timeline-element-icon bounce-in"><i
                                                                    class="badge badge-dot badge-dot-xl badge-danger">
                                                                </i></span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title">Build the
                                                                    production release</h4>
                                                                <p>Lorem ipsum dolor sit
                                                                    amit,consectetur eiusmdd tempor
                                                                    incididunt ut labore et dolore magna
                                                                    elit enim at minim veniam quis
                                                                    nostrud</p><span
                                                                    class="vertical-timeline-element-date"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                        <div><span class="vertical-timeline-element-icon bounce-in"><i
                                                                    class="badge badge-dot badge-dot-xl badge-primary">
                                                                </i></span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-success">
                                                                    Something not important</h4>
                                                                <p>Lorem ipsum dolor sit
                                                                    amit,consectetur elit enim at minim
                                                                    veniam quis nostrud</p><span
                                                                    class="vertical-timeline-element-date"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab-errors-header1" role="tabpanel">
                                    <div class="scroll-area-sm">
                                        <div class="scrollbar-container">
                                            <div class="no-results pt-3 pb-0">
                                                <div class="swal2-icon swal2-success swal2-animate-success-icon">
                                                    <div class="swal2-success-circular-line-left"
                                                        style="background-color: rgb(255, 255, 255);">
                                                    </div>
                                                    <span class="swal2-success-line-tip"></span>
                                                    <span class="swal2-success-line-long"></span>
                                                    <div class="swal2-success-ring"></div>
                                                    <div class="swal2-success-fix"
                                                        style="background-color: rgb(255, 255, 255);">
                                                    </div>
                                                    <div class="swal2-success-circular-line-right"
                                                        style="background-color: rgb(255, 255, 255);">
                                                    </div>
                                                </div>
                                                <div class="results-subtitle">All caught up!</div>
                                                <div class="results-title">There are no system
                                                    errors!</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <ul class="nav flex-column">
                                <li class="nav-item-divider nav-item"></li>
                                <li class="nav-item-btn text-center nav-item">
                                    <button class="btn-shadow btn-wide btn-pill btn btn-focus btn-sm">View
                                        Latest Changes</button>
                                </li>
                            </ul>
                        </div>
                    </div> --}}
                    <div class="dots-separator"></div>
                </div>
            </div>
            {{-- <div class="app-footer-right">
                <ul class="header-megamenu nav">
                    <li class="nav-item">
                        <a data-placement="top" rel="popover-focus" data-offset="300" data-toggle="popover-custom"
                            class="nav-link">
                            Grid Menu
                            <div class="badge badge-dark ml-0 ml-1">
                                <small>NEW</small>
                            </div>
                            <i class="fa fa-angle-up ml-2 opacity-8"></i>
                        </a>
                        <div class="rm-max-width rm-pointers">
                            <div class="d-none popover-custom-content">
                                <div class="dropdown-menu-header">
                                    <div class="dropdown-menu-header-inner bg-tempting-azure">
                                        <div class="menu-header-image opacity-1"
                                            style="background-image: url('{{ asset('architect/images/dropdown-header/city5.jpg') }}');">
                                        </div>
                                        <div class="menu-header-content text-dark">
                                            <h5 class="menu-header-title">Two Column Grid</h5>
                                            <h6 class="menu-header-subtitle">Easy grid navigation
                                                inside popovers</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid-menu grid-menu-2col">
                                    <div class="no-gutters row">
                                        <div class="col-sm-6">
                                            <button
                                                class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-dark"><i
                                                    class="lnr-lighter text-dark opacity-7 btn-icon-wrapper mb-2">
                                                </i>Automation
                                            </button>
                                        </div>
                                        <div class="col-sm-6">
                                            <button
                                                class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger"><i
                                                    class="lnr-construction text-danger opacity-7 btn-icon-wrapper mb-2">
                                                </i>Reports
                                            </button>
                                        </div>
                                        <div class="col-sm-6">
                                            <button
                                                class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-success"><i
                                                    class="lnr-bus text-success opacity-7 btn-icon-wrapper mb-2">
                                                </i>Activity
                                            </button>
                                        </div>
                                        <div class="col-sm-6">
                                            <button
                                                class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-focus"><i
                                                    class="lnr-gift text-focus opacity-7 btn-icon-wrapper mb-2">
                                                </i>Settings
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <ul class="nav flex-column">
                                    <li class="nav-item-divider nav-item"></li>
                                    <li class="nav-item-btn clearfix nav-item">
                                        <div class="float-left">
                                            <button class="btn btn-link btn-sm">Link Button</button>
                                        </div>
                                        <div class="float-right">
                                            <button class="btn-shadow btn btn-info btn-sm">Info
                                                Button</button>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div> --}}
        </div>
    </div>
</div>
