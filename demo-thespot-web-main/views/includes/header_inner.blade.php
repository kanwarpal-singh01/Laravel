<?php $url = Request::segments();?>
@php $locale = App::getLocale(); @endphp
<div class="top_header">
    <div class="container">
        <nav class="navbar navbar-expand-lg scrolling-navbar">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent2"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent2">
                <ul class="navbar-nav mr-auto">
                    <li class="language_box">
                        <div class="dropdown">
                            <button class="dropdown-toggle" type="button" data-toggle="dropdown" id="current_language">
                                @if($locale == 'en')
                                {{__('messages.header_inner.english')}}
                                @elseif($locale == 'de')
                                {{__('messages.header_inner.german')}}
                                @else
                                {{__('messages.header_inner.english')}}
                                @endif
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a class="@if($locale == 'en'){{'active'}}@endif"
                                        href="{{url('language/en')}}">{{__('messages.header_inner.english')}}</a></li>
                                <li><a class="@if($locale == 'de'){{'active'}}@endif"
                                        href="{{url('language/de')}}">{{__('messages.header_inner.german')}}</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item"><a class="nav-link"
                            href="{{url('dmeur')}}">{{__('messages.header_inner.dm_euro_exchnage')}}</a></li>
                    <li class="nav-item"><a class="nav-link {{(isset($url[0]) &&  $url[0]=='home')? 'active':''}}"
                            href="{{url('auction')}}">{{__('messages.header_inner.home')}}</a></li>
                    <li class="nav-item"><a class="nav-link {{(isset($url[2]) &&  $url[2]=='create')? 'active':''}}"
                            href="{{url('customer/products/create')}}">{{__('messages.header_inner.sell')}}</a></li>

                    <li class="nav-item"><a class="nav-link {{(isset($url[2]) &&  $url[2]=='blog')? 'active':''}}"
                            href="{{url('blog')}}">{{__('messages.blog')}}</a></li>
                    <li class="nav-item">
                        <a class="nav-link {{isset($url[0]) && $url[0] =='bonus'?'active':''}}"
                            href="{{url('bonus')}}">{{__('messages.bonus_program')}}</a>
                    </li>
                    @php $header = DB::table("cmspage")->where(['status'=>1, 'menu_type'=>3])->get() @endphp
                    @foreach($header as $row)
                    <li class="nav-item"><a
                            class="nav-link  @if(isset($url[2]) && $url[2] == $row->slug){{'active'}}@endif"
                            href="{{url('/')}}/{{$row->slug}}">@if($locale == "en"){{$row->title_en}}@else
                            {{$row->title_de}} @endif</a></li>
                    @endforeach
                    <li class="nav-item">
                        <a class="nav-link {{isset($url[0]) && $url[0] =='b2b'?'active':''}}"
                            href="{{url('b2b')}}">{{__('messages.b2b_packages')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{isset($url[0]) && $url[0] =='reports'?'active':''}}"
                            href="{{url('reports')}}">{{__('messages.report')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{isset($url[0]) && $url[0] =='newsletter'?'active':''}}"
                            href="{{url('newsletter')}}">{{__('messages.newsletter')}}</a>
                    </li>
                </ul>
                <ul class="float-right navbar-nav login_signup notification_myaccount before_login">
                    <?php
                  if(Auth::check()){
                      		 $user = Auth::user();
                      		 if($user->status ==1 && $user->role == 'customer'){ ?>
                    <li class="nav-item profile_box">
                        <div class="dropdown">
                            <button class="dropdown-toggle" type="button"
                                data-toggle="dropdown">{{ucfirst($user->first_name)}}
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li class="@if(isset($url[1]) && $url[1] == 'updateprofile'){{'active'}}@endif"><a
                                        href="{{url('').'/'."customer".'/updateprofile'}}">{{__('messages.header_inner.my_account')}}</a>
                                </li>
                                <li><a href="javascript:;" class="customer_logout"
                                        onclick="logout()">{{__('messages.header_inner.logout')}}</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item notification ">
                        <a class="nav-link dropdownNotification" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="true" data-id="{{Auth::user()->id}}">
                            <i class="fas fa-bell"></i>
                            <?php $notificationData = Helper::getnotification($user->id);?>
                            @if(Helper::getnotificationCount($user->id) > 0)
                            <span
                                class="notification_myaccount_count num">{{Helper::getnotificationCount($user->id)}}</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownNotification">
                            <li class="title">{{__('messages.header_inner.notifications')}}</li>
                            @foreach($notificationData as $key => $value)
                            <li>
                                <a class="notificationss"
                                    href="{{url('customer/notificationhistory#'.$value->id)}}">{{strip_tags($value->description)}}</a>
                            </li>
                            @endforeach
                            <li class="footer">
                                <a
                                    href="{{url('').'/'.Auth::user()->role.'/notificationhistory'}}">{{__('messages.header_inner.see_all')}}</a>
                            </li>
                        </ul>
                    </li>
                    <?php }elseif($user->status ==1 && $user->role == 'vendor'){ ?>
                    <li class="nav-item profile_box">
                        <div class="dropdown">
                            <button class="dropdown-toggle" type="button"
                                data-toggle="dropdown">{{ucfirst($user->first_name)}}
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a
                                        href="{{url('').'/'.'vendor'.'/updateprofile'}}">{{__('messages.header_inner.my_account')}}</a>
                                </li>
                                <li><a href="javascript:;" class="vendor_logout"
                                        onclick="logout()">{{__('messages.header_inner.logout')}}</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item notification ">
                        <a class="nav-link dropdownNotification" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="true" data-id="{{Auth::user()->id}}">
                            <i class="fas fa-bell"></i>
                            <?php $notificationData = Helper::getnotification($user->id);?>
                            @if(Helper::getnotificationCount($user->id) > 0)
                            <span
                                class="notification_myaccount_count num">{{Helper::getnotificationCount($user->id)}}</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownNotification">
                            <li class="title">{{__('messages.header_inner.notifications')}}</li>
                            @foreach($notificationData as $key => $value)
                            <li>
                                <a class="notificationss"
                                    href="{{url('vendor/notificationhistory#'.$value->id)}}">{{strip_tags($value->description)}}fdsf</a>
                            </li>
                            @endforeach
                            <li class="footer">
                                <a
                                    href="{{url('').'/'.Auth::user()->role.'/notificationhistory'}}">{{__('messages.header_inner.see_all')}}</a>
                            </li>
                        </ul>
                    </li>
                    <?php }elseif($user->status ==1 && $user->role == 'admin'){ ?>
                    <li class="nav-item profile_box">
                        <div class="dropdown">
                            <button class="dropdown-toggle" type="button"
                                data-toggle="dropdown">{{ucfirst($user->first_name)}}
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a
                                        href="{{url('').'/'.'admin'.'/dashboard'}}">{{__('messages.header_inner.my_account')}}</a>
                                </li>
                                <li><a href="javascript:;" class="vendor_logout"
                                        onclick="logout()">{{__('messages.header_inner.logout')}}</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item"><a class="nav-link bell_box" href="javascript:;"><i
                                class="fas fa-bell"></i></a></li>
                    <?php } }else{ ?>
                    <li class="nav-item {{(isset($url[0]) &&  $url[0]=='register')?'active_new':''}}"><a
                            class="nav-link register " href="{{url('register/customer')}}">
                            {{__('messages.header_inner.register')}}</a>
                    </li>
                    <li class="nav-item {{(isset($url[0]) &&  $url[0]=='login')?'active_new':''}}"><a
                            class="nav-link signin" href="{{url('login')}}">{{__('messages.header_inner.sign_in')}}</a>
                    </li>
                    <?php } ?>
                    @if(!isset(Auth::user()->role) ||(isset(Auth::user()->role)) && Auth::user()->role == 'customer')
                    <li class="nav-item"><a class="nav-link cart_box" href="{{url('cart/viewcart')}}"><i
                                class="fas fa-shopping-cart"></i><span
                                class="cart_counter">{{Helper::cart_count()}}</span></a></li>
                    @endif
                </ul>
            </div>
        </nav>
    </div>
</div>
<div class="js-cookie-consent cookie-consent">
    <div class="modal fade show" role="dialog" style="padding-right: 12px; display: block;">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group">
                        <span class="cookie-consent__message">
                            {{trans("messages.allow_cookies_content")}}
                        </span>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="form_submit_button">
                        <button
                            class="btn sitebtn_c waves-effect waves-light js-cookie-consent-agree cookie-consent__agree"
                            type="button">{{trans("messages.allow_cookies")}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function logout() {
    var result = confirm("{{__('messages.header_inner.logout_text')}}");
    if (result == false) {
        return false;
    } else {
        location.href = "{{url('/logout')}}";
    }
}

$('body').on('click', '.notification', function() {
    <
    ? php
    if (isset(Auth::user() - > role)) {
        ? >
        var role = '{{url("/")."/".Auth::user()->role."/updatenotification"}}';
        $.ajax({
                type: 'POST',
                url: role,
                data: '',
                success: function(data) {
                    $(".num").hide();
                }
            }) <
            ? php
    } ? >

})

$(document).on("click", '.dropdownnotification', function(e) {
    //notification_history
    alert()
    $(this).find(".notification_history").slideToggle();
});
</script>
<script>
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

    //console.log('xcvcxv');
    console.log(cookieExists('laravel_cookie_consent'));

    if (cookieExists('laravel_cookie_consent')) {
        hideCookieDialog();
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
$('.close').click(function() {
    $('.modal').hide();
})
</script>
