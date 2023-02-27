<?php $url = Request::segments();?>
<div class="site_content header-2">
    <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="{{url('/')}}">
                    <img src="{{asset('img/logo.png')}}">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#basicExampleNav"
                    aria-controls="basicExampleNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="basicExampleNav">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link {{isset($url[0]) && $url[0] =='aboutus'?'active':''}}"
                                href="{{url('aboutus')}}">{{__('messages.header_inner.about_us')}}</a>
                        </li>
                        <li class="nav-item dropdown custome-dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">{{__('messages.header_inner.our_services')}}</a>
                            <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item {{isset($url[0]) && $url[0] =='dmeur'?'active':''}}"
                                    href="{{url('dmeur')}}">{{__('messages.dm_eur_exchange')}}</a>
                                <a class="dropdown-item "
                                    href="{{url('auction')}}">{{__('messages.header_inner.epoxa_auction')}}</a>
                                <a class="dropdown-item {{isset($url[0]) && $url[0] =='marketing'?'active':''}}"
                                    href="{{url('marketing')}}">{{__('messages.marketing_services')}}</a>
                                <a class="dropdown-item {{isset($url[0]) && $url[0] =='b2b'?'active':''}}"
                                    href="{{url('b2b')}}">{{__('messages.b2b_packages')}}</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" target="_blank"
                                href="{{url('auction')}}">{{__('messages.header_inner.epoxa_auction')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{isset($url[0]) && $url[0] =='blog'?'active':''}}"
                                href="{{url('blog')}}">{{__('messages.blog')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{isset($url[0]) && $url[0] =='newsletter'?'active':''}}"
                                href="{{url('newsletter')}}">{{__('messages.newsletter')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{isset($url[0]) && $url[0] =='bonus'?'active':''}}"
                                href="{{url('bonus')}}">{{__('messages.bonus_program')}}</a>
                        </li>


                    </ul>
                    <div class="nav-item" id="SkypeButton_Call_devtechnosys_1"></div>
                    <div class="my-0">
                        <div class="country-drop dropdown">
                            <a class="nav-link dropdown-toggle d-flex" href="{{url('language/en')}}"
                                id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                @if($locale == 'en')
                                <figure class="obj-fit">
                                    <img src="{{asset('landing/img/new-images/country.png')}}">
                                </figure>
                                <span>{{__('messages.header_inner.english')}}</span>

                                @elseif($locale == 'de')
                                <figure class="obj-fit">
                                    <img src="{{asset('landing/img/germany-flag.png')}}">
                                </figure>
                                <span>{{__('messages.header_inner.german')}}</span>
                                @else
                                <figure class="obj-fit">
                                    <img src="{{asset('landing/img/new-images/country.png')}}">
                                </figure>
                                <span> {{__('messages.header_inner.english')}}</span>
                                @endif

                            </a>
                            <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item nav-link d-flex"
                                    href="@if($locale == 'en') {{url('language/de')}} @else {{url('language/en')}} @endif">
                                    @if($locale == 'en')
                                    <figure class="obj-fit">
                                        <img src="{{asset('landing/img/germany-flag.png')}}">
                                    </figure>
                                    <span>{{__('messages.header_inner.german')}}</span>
                                    @elseif($locale == 'de')
                                    <figure class="obj-fit">
                                        <img src="{{asset('landing/img/new-images/country.png')}}">
                                    </figure>
                                    <span>{{__('messages.header_inner.english')}}</span>
                                    @else
                                    <figure class="obj-fit">
                                        <img src="{{asset('landing/img/new-images/country.png')}}">
                                    </figure>
                                    <span> {{__('messages.header_inner.english')}}</span>
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <script async type="text/javascript" src="https://secure.skypeassets.com/i/scom/js/skype-uri.js"></script>
    <script type="text/javascript">
    Skype.ui({
        "name": "chat",
        "element": "SkypeButton_Call_devtechnosys_1",
        "participants": ["{{config('Settings.skype_name')}}"],
        "imageSize": 24,
        'listParticipants': true
    });

    window.laravelCookieConsent = (function() {

        const COOKIE_VALUE = 1;

        function consentWithCookies() {
            setCookie('laravel_cookie_consent', COOKIE_VALUE, 7300);
            hideCookieDialog();
        }

        function cookieExists(name) {
            return (document.cookie.split('; ').indexOf(name + '=' + COOKIE_VALUE) !== -1);
        }

        function hideCookieDialog() {
            const dialogs = document.getElementsByClassName('js-cookie-consent');

            for (let i = 0; i < dialogs.length; ++i) {
                dialogs[i].style.display = 'none';
            }
        }

        function setCookie(name, value, expirationInDays) {
            const date = new Date();
            date.setTime(date.getTime() + (expirationInDays * 24 * 60 * 60 * 1000));
            document.cookie = name + '=' + value + '; ' + 'expires=' + date.toUTCString() + ';path=/';
        }



        if (cookieExists('laravel_cookie_consent')) {
            hideCookieDialog();
        } else {
            const dialogs = document.getElementsByClassName('js-cookie-consent');

            for (let i = 0; i < dialogs.length; ++i) {
                dialogs[i].style.display = 'block';
            }
        }

        const buttons = document.getElementsByClassName('js-cookie-consent-agree');

        for (let i = 0; i < buttons.length; ++i) {
            buttons[i].addEventListener('click', consentWithCookies);
        }

        return {
            consentWithCookies: consentWithCookies,
            hideCookieDialog: hideCookieDialog
        };
    })();
    </script>
    <style type="text/css">
    .nav-link.waves-effect.waves-light.active {
        color: #0055a0;
    }
    </style>