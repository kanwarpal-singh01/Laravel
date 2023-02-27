<nav class="pcoded-navbar">
    <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
    <div class="pcoded-inner-navbar main-menu">
        <div class="">
            <div class=" main-menu-header">
            @if (auth('admin')->user()->profile_image)
                <img class="img-80 img-radius"
                    src="{{ asset('public/storage/uploads/users/' . auth('admin')->user()->profile_image ?? '') }}"
                    alt="User-Profile-Image">
            @else
                <img class="img-80 img-radius" src="{{ asset('public/storage/uploads/users/avtar.png') }}"
                    alt="User-Profile-Image">
            @endif
            <div class="user-details">
                <span
                    id="more-details">{{ \Illuminate\Support\Str::limit(ucfirst(auth('admin')->user()->first_name . ' ' . auth('admin')->user()->last_name), 15, $end = '...') }}<i
                        class="fa fa-caret-down"></i></span>
            </div>
        </div>
        <div class="main-menu-content">
            <ul>
                <li class="more-details">
                    <a href="{{ route('admin.profile.update.view') }}"><i class="ti-user"></i>View Profile</a>
                    <a href="{{ route('admin.change.password.view') }}"><i class="ti-unlock"></i>Change
                        Password</a>
                    @guest
                        <a href="{{ route('admin.login.view') }}"><i class="ti-layout-sidebar-left"></i>Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"><i class="ti-layout-sidebar-left"></i>Register</a>
                        @endif
                    @else
                        <a href="{{ route('admin.logout') }}"
                            onclick="event.preventDefault();
                                                                                 document.getElementById('logout-form').submit();">
                            <i class="ti-power-off"></i>Logout
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                                class="d-none">
                                @csrf
                            </form>
                        </a>
                    @endguest
                </li>
            </ul>
        </div>
    </div>
    <div class="p-15 p-b-0">
    </div>
    <?php $segment2 = Request::segment(2); ?>
    <ul class="pcoded-item pcoded-left-item">
        <li class="pcoded-hasmenu active {{ $segment2 == 'dashboard' ? 'pcoded-trigger' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="waves-effect waves-dark">
                <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
                <span class="pcoded-mtext">Dashboard</span>
                <span class="pcoded-mcaret"></span>
            </a>
        </li>
    </ul>

    <ul class="pcoded-item pcoded-left-item">
        <li
            class="pcoded-hasmenu active {{ $segment2 == 'banner-management' || $segment2 == 'about-us-management' || $segment2 == 'features-management' || $segment2 == 'video-management' || $segment2 == 'testimonial' || $segment2 == 'screenshot-management' || $segment2 == 'footer-management' || $segment2 == 'menu-management' || $segment2 == 'pop-up-management' || $segment2 == 'market-place' ? 'pcoded-trigger' : '' }}">

            <a href="#" class="waves-effect waves-dark">
                <span class="pcoded-micon"><i class="ti-layout-grid2-alt"></i><b>WM</b></span>
                <span class="pcoded-mtext">Website Management</span>
                <span class="pcoded-mcaret"></span>
            </a>
            <ul class="pcoded-submenu">

                <li class="{{ $segment2 === 'banner-management' ? 'active' : '' }} ">
                    <a href="{{ route('admin.banner.management.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">Banner Management</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>

                <li class="{{ $segment2 === 'about-us-management' ? 'active' : '' }}">
                    <a href="{{ route('admin.home.section1.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">About Us Management</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>

                <li class="{{ $segment2 === 'features-management' ? 'active' : '' }}">
                    <a href="{{ route('admin.home.section2.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">Features Management</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>

                <li class="{{ $segment2 === 'video-management' ? 'active' : '' }}">
                    <a href="{{ route('admin.home.section3.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">Video Management</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>

                <li class="{{ $segment2 === 'testimonial' ? 'active' : '' }} ">

                    <a href="{{ route('admin.testimonial.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">Testimonial Management</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                <li class="{{ $segment2 === 'screenshot-management' ? 'active' : '' }}">
                    <a href="{{ route('admin.home.section5.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">Screenshot Management</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>

                {{-- <li class="{{$segment2 === 'plan-management' ? 'active' : ''}}"> --}}
                {{-- <a href="{{route('admin.home.section6.list')}}" class="waves-effect waves-dark"> --}}
                {{-- <span class="pcoded-micon"><i class="ti-angle-right"></i></span> --}}
                {{-- <span class="pcoded-mtext">Plans Management</span> --}}
                {{-- <span class="pcoded-mcaret"></span> --}}
                {{-- </a> --}}
                {{-- </li> --}}
                {{-- <li class="{{$segment2 === 'coupon' ? 'active' : ''}} "> --}}
                {{-- <a href="{{route('admin.coupon.list')}}" class="waves-effect waves-dark"> --}}
                {{-- <span class="pcoded-micon"><i class="ti-angle-right"></i></span> --}}
                {{-- <span class="pcoded-mtext">Coupon Management</span> --}}
                {{-- <span class="pcoded-mcaret"></span> --}}
                {{-- </a> --}}
                {{-- </li> --}}
                {{-- <li class="{{$segment2 === 'content-management' ? 'active' : ''}} "> --}}
                {{-- <a href="{{route('admin.content.management.list')}}" class="waves-effect waves-dark"> --}}
                {{-- <span class="pcoded-micon"><i class="ti-angle-right"></i></span> --}}
                {{-- <span class="pcoded-mtext">Legal</span> --}}
                {{-- <span class="pcoded-mcaret"></span> --}}
                {{-- </a> --}}
                {{-- </li> --}}

                <li class="{{ $segment2 === 'footer-management' ? 'active' : '' }}">
                    <a href="{{ route('admin.footer.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">Footer Management</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>

                <li class="{{ $segment2 === 'menu-management' ? 'active' : '' }}">
                    <a href="{{ route('admin.menu.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">Menu Management</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                <li class="{{ $segment2 === 'pop-up-management' ? 'active' : '' }}">
                    <a href="{{ route('admin.popup.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">PopUp Management</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>


                <li class="{{ $segment2 === 'market-place' ? 'active' : '' }}">
                    <a href="{{ route('admin.market.place.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">Market Place</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>


            </ul>
        </li>
    </ul>

    <ul class="pcoded-item pcoded-left-item">
        <li
            class="pcoded-hasmenu active {{ $segment2 == 'knowledge-center' || $segment2 == 'category-management' ? 'pcoded-trigger' : '' }}">
            <a href="#" class="waves-effect waves-dark">
                <span class="pcoded-micon"><i class="ti-layout-grid2-alt"></i><b>AM</b></span>
                <span class="pcoded-mtext">Application Management</span>
                <span class="pcoded-mcaret"></span>
            </a>
            <ul class="pcoded-submenu">
                {{-- <li class="{{$segment2 === 'faq-management' ? 'active' : ''}} "> --}}
                {{-- <a href="{{route('admin.faq.management.list')}}" class="waves-effect waves-dark"> --}}
                {{-- <span class="pcoded-micon"><i class="ti-angle-right"></i></span> --}}
                {{-- <span class="pcoded-mtext">FAQ Management</span> --}}
                {{-- <span class="pcoded-mcaret"></span> --}}
                {{-- </a> --}}
                {{-- </li> --}}

                <li class="{{ $segment2 === 'category-management' ? 'active' : '' }} ">
                    <a href="{{ route('admin.category.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">Category Management</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>

                <li class="{{ $segment2 === 'knowledge-center' ? 'active' : '' }} ">
                    <a href="{{ route('admin.knowledge.center.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">Knowledge Center</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                {{-- <li class="{{$segment2 === 'intro-screen' ? 'active' : ''}} "> --}}
                {{-- <a href="{{route('admin.intro.screen.list')}}" class="waves-effect waves-dark"> --}}
                {{-- <span class="pcoded-micon"><i class="ti-angle-right"></i></span> --}}
                {{-- <span class="pcoded-mtext">Intro Screen</span> --}}
                {{-- <span class="pcoded-mcaret"></span> --}}
                {{-- </a> --}}
                {{-- </li> --}}




            </ul>
        </li>
    </ul>
    {{-- <ul class="pcoded-item pcoded-left-item"> --}}
    {{-- <li class="pcoded-hasmenu active pcoded-trigger"> --}}
    {{-- <a href="javascript:void(0)" class="waves-effect waves-dark"> --}}
    {{-- <span class="pcoded-micon"><i class="ti-layout-grid2-alt"></i><b>BC</b></span> --}}
    {{-- <span class="pcoded-mtext">Global</span> --}}
    {{-- <span class="pcoded-mcaret"></span> --}}
    {{-- </a> --}}
    {{-- <ul class="pcoded-submenu"> --}}

    {{-- <li class="{{$segment2 === 'user-management' ? 'active' : ''}}"> --}}
    {{-- <a href="{{route('admin.user.list')}}" class="waves-effect waves-dark"> --}}
    {{-- <span class="pcoded-micon"><i class="ti-angle-right"></i></span> --}}
    {{-- <span class="pcoded-mtext">User Management</span> --}}
    {{-- <span class="pcoded-mcaret"></span> --}}
    {{-- </a> --}}
    {{-- </li> --}}

    {{-- <li class="{{$segment2 === 'document-management' ? 'active' : ''}}"> --}}
    {{-- <a href="{{route('admin.document.list')}}" class="waves-effect waves-dark"> --}}
    {{-- <span class="pcoded-micon"><i class="ti-angle-right"></i></span> --}}
    {{-- <span class="pcoded-mtext">Document Management</span> --}}
    {{-- <span class="pcoded-mcaret"></span> --}}
    {{-- </a> --}}
    {{-- </li> --}}

    {{-- <li class="{{$segment2 === 'global-setting-management' ? 'active' : ''}}"> --}}
    {{-- <a href="{{route('admin.global.setting.management.edit', base64_encode($global_setting->id ?? ''))}}" class="waves-effect waves-dark" > --}}
    {{-- <span class="pcoded-micon"><i class="ti-angle-right"></i></span> --}}
    {{-- <span class="pcoded-mtext">Global Setting</span> --}}
    {{-- <span class="pcoded-mcaret"></span> --}}
    {{-- </a> --}}
    {{-- </li> --}}

    {{-- <li class="{{$segment2 === 'email-template-management' ? 'active' : ''}} "> --}}
    {{-- <a href="{{route('admin.email.template.management.list')}}" class="waves-effect waves-dark"> --}}
    {{-- <span class="pcoded-micon"><i class="ti-angle-right"></i></span> --}}
    {{-- <span class="pcoded-mtext">Email Management</span> --}}
    {{-- <span class="pcoded-mcaret"></span> --}}
    {{-- </a> --}}
    {{-- </li> --}}

    {{-- <li class="{{$segment2 === 'template-management' ? 'active' : ''}} "> --}}
    {{-- <a href="{{route('admin.template.list')}}" class="waves-effect waves-dark"> --}}
    {{-- <span class="pcoded-micon"><i class="ti-angle-right"></i></span> --}}
    {{-- <span class="pcoded-mtext">Template Management</span> --}}
    {{-- <span class="pcoded-mcaret"></span> --}}
    {{-- </a> --}}
    {{-- </li> --}}

    {{-- <li class="{{$segment2 === 'contact-us' ? 'active' : ''}} "> --}}
    {{-- <a href="{{route('admin.contact.list')}}" class="waves-effect waves-dark"> --}}
    {{-- <span class="pcoded-micon"><i class="ti-angle-right"></i></span> --}}
    {{-- <span class="pcoded-mtext">Contact Us</span> --}}
    {{-- <span class="pcoded-mcaret"></span> --}}
    {{-- </a> --}}
    {{-- </li> --}}

    {{-- </ul> --}}
    {{-- </li> --}}
    {{-- </ul> --}}
    <ul class="pcoded-item pcoded-left-item">
        <li
            class="pcoded-hasmenu active {{ $segment2 == 'user-reports' || $segment2 == 'marketing' ? 'pcoded-trigger' : '' }}">
            <a href="#" class="waves-effect waves-dark">
                <span class="pcoded-micon"><i class="ti-layout-grid2-alt"></i><b>RM</b></span>
                <span class="pcoded-mtext">Reports</span>
                <span class="pcoded-mcaret"></span>
            </a>
            <ul class="pcoded-submenu">
                <li class="{{ $segment2 === 'user-reports' ? 'active' : '' }}">
                    <a href="{{ route('admin.user.reports') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">User Reports</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                <li class="{{ $segment2 === 'marketing' ? 'active' : '' }}">
                    <a href="{{ route('admin.marketing') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">Marketing</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
    {{-- changes in admin panel --}}
    <ul class="pcoded-item pcoded-left-item">
        <li
            class="pcoded-hasmenu active {{ $segment2 == 'plan-management' || $segment2 == 'coupon' || $segment2 == 'notary-details' ? 'pcoded-trigger' : '' }}">
            <a href="#" class="waves-effect waves-dark">
                <span class="pcoded-micon"><i class="ti-layout-grid2-alt"></i><b>BM</b></span>
                <span class="pcoded-mtext">Billing</span>
                <span class="pcoded-mcaret"></span>
            </a>
            <ul class="pcoded-submenu">

                <li class="{{ $segment2 === 'plan-management' ? 'active' : '' }}">
                    <a href="{{ route('admin.home.section6.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">Subscription Plans</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                <li class="{{ $segment2 === 'coupon' ? 'active' : '' }} ">
                    <a href="{{ route('admin.coupon.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">Promo Codes</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>

                <li class="{{ $segment2 === 'notary-details' ? 'active' : '' }} ">
                    <a href="{{ route('admin.notary.details.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">Notary Details</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>

    <ul class="pcoded-item pcoded-left-item">
        <li
            class="pcoded-hasmenu active {{ $segment2 == 'keyword' || $segment2 == 'template-management' || $segment2 == 'email-template-management' || $segment2 == 'document-management' || $segment2 == 'user-management' ? 'pcoded-trigger' : '' }}">
            <a href="#" class="waves-effect waves-dark">
                <span class="pcoded-micon"><i class="ti-layout-grid2-alt"></i><b>UD</b></span>
                <span class="pcoded-mtext">Users & Document</span>
                <span class="pcoded-mcaret"></span>
            </a>
            <ul class="pcoded-submenu">
                <li class="{{ $segment2 === 'keyword' ? 'active' : '' }} ">
                    <a href="{{ route('admin.keyword.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">keyword Management</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>

                <li class="{{ $segment2 === 'template-management' ? 'active' : '' }} ">
                    <a href="{{ route('admin.template.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">Template Management</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>

                <li class="{{ $segment2 === 'email-template-management' ? 'active' : '' }} ">
                    <a href="{{ route('admin.email.template.management.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">Email Management</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>

                <li class="{{ $segment2 === 'document-management' ? 'active' : '' }}">
                    <a href="{{ route('admin.document.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">Document Database</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                <li class="{{ $segment2 === 'user-management' ? 'active' : '' }}">
                    <a href="{{ route('admin.user.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">App Users</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>

    <ul class="pcoded-item pcoded-left-item">
        <li
            class="pcoded-hasmenu active {{ $segment2 == 'intro-screen' || $segment2 == 'global-setting-management' ? 'pcoded-trigger' : '' }}">
            <a href="#" class="waves-effect waves-dark">
                <span class="pcoded-micon"><i class="ti-layout-grid2-alt"></i><b>GS</b></span>
                <span class="pcoded-mtext">Global Settings</span>
                <span class="pcoded-mcaret"></span>
            </a>
            <ul class="pcoded-submenu">
                <li class="{{ $segment2 === 'intro-screen' ? 'active' : '' }} ">
                    <a href="{{ route('admin.intro.screen.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">App Intro Screens</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>

                <li class="{{ $segment2 === 'global-setting-management' ? 'active' : '' }}">
                    <a href="{{ route('admin.global.setting.management.edit', base64_encode($global_setting->id ?? '')) }}"
                        class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">General Details</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>

            </ul>
        </li>
    </ul>

    <ul class="pcoded-item pcoded-left-item">
        <li
            class="pcoded-hasmenu active {{ $segment2 == 'content-management' || $segment2 == 'faq-management' || $segment2 == 'sms-content' || $segment2 == 'content-management-for-android' ? 'pcoded-trigger' : '' }}">
            <a href="#" class="waves-effect waves-dark">
                <span class="pcoded-micon"><i class="ti-layout-grid2-alt"></i><b>LS</b></span>
                <span class="pcoded-mtext">Legal & Support</span>
                <span class="pcoded-mcaret"></span>
            </a>
            <ul class="pcoded-submenu">
                <li class="{{ $segment2 === 'content-management' ? 'active' : '' }} ">
                    <a href="{{ route('admin.content.management.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">Legal For IOS</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>

                <li class="{{ $segment2 === 'content-management-for-android' ? 'active' : '' }} ">
                    <a href="{{ route('admin.content.management.list.android') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">Legal For Android</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>

                <li class="{{ $segment2 === 'sms-content' ? 'active' : '' }} ">
                    <a href="{{ route('admin.sms.content.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">SMS Content</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>

                <li class="{{ $segment2 === 'faq-management' ? 'active' : '' }} ">
                    <a href="{{ route('admin.faq.management.list') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext">FAQ's</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>

            </ul>
        </li>
    </ul>
    {{-- changes in admin panel --}}
    </div>
</nav>
