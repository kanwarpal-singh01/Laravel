<!-- -------footer-section--------- -->
<footer>
    <div class="container">
        <div class="footer-download-app">
            @if($footer)
            <div class="row">
                <div class="col-md-7">
                    <div class="download-stores-apps">
                        <h3 class="head-line-hm">{{$footer->title ?? ''}}</h3>
                        <div class="stores-btns">
                            <a href="#" class="btn btn-green" data-toggle="{{!empty($popup['status']) ? 'modal' : ''}}" data-target="{{!empty($popup['status']) ? '#exampleModalCenter' : ''}}" id="app-store-id"><i class="ri-app-store-fill"></i> App Store</a>
                            <a href="#" class="btn btn-yellow btn-white" data-toggle="{{!empty($popup['status']) ? 'modal' : ''}}" data-target="{{!empty($popup['status']) ? '#exampleModalCenter' : ''}}" id="google-pay-id"><i class="ri-google-play-fill"></i> Google Play</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="app-case-banner">
                        <img src="{{asset('public/storage/uploads/footer/'.$footer->image ?? '')}}">
                    </div>
                </div>
            </div>
                @endif
        </div>

        <div class="footer-main">
            <div class="row">
                <div class="col-md-3 footer-logo-box">
                    <a href="{{route('home')}}" class="logo-footer">
                        <img src="{{asset('public/front-end/images/logo.svg')}}">
                    </a>
                    @if($footer)
                    <p>{{$footer->content ?? ''}}</p>
                    @endif
                </div>
                <div class="col-md-3">
                    <h4 class="footer-tit" >Explore</h4>
                    <ul class="footer-link">
                        @forelse($footer_explore as $menu_data)
                            <li>
                                <a href="{{menu_route($menu_data->slug)}}">{{ucfirst($menu_data->title)}}</a>
                            </li>
                        @empty
                        @endforelse
{{--                        <li>--}}
{{--                            <a href="{{route('home')}}">Home</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{route('about.us')}}">About us</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{url('/home/#testimonial')}}">Testimonials</a>--}}
{{--                        </li>--}}

{{--                        <li>--}}
{{--                            <a href="{{route('knowledge.center')}}">Knowledge Center</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{route('marketplace')}}">Marketplace</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{url('/home/#plan')}}">Pricing</a>--}}
{{--                        </li>--}}

                    </ul>
                </div>
                <div class="col-md-3">
                    <h4 class="footer-tit" >Help</h4>
                    <ul class="footer-link">
                        @forelse($footer_help as $menu_data)
                            <li>
                                <a href="{{menu_route($menu_data->slug)}}">{{ucfirst($menu_data->title)}}</a>
                            </li>
                        @empty
                        @endforelse

{{--                        <li>--}}
{{--                            <a href="{{route('terms.conditions')}}">Terms Of Use</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{route('privacy.policy')}}">Privacy Policy</a>--}}
{{--                        </li>--}}

                    </ul>
                </div>
                <div class="col-md-3">
                    <h4 class="footer-tit" >Follow Us</h4>
                    <ul class=" footer-link footer-social">
                        <li>
                            <a href="{{$global_setting_footer->facebook_link ?? ''}}" target="_blank"><i class="ri-facebook-fill"></i></a>
                        </li>
{{--                        <li>--}}
{{--                            <a href="{{$global_setting_footer->google_plus ?? ''}}"><i class="ri-youtube-fill"></i></a>--}}
{{--                        </li>--}}
                        <li>
                            <a href="{{$global_setting_footer->twitter_link ?? ''}}" target="_blank"><i class="ri-twitter-fill"></i></a>
                        </li>
                        <li>
                            <a href="{{$global_setting_footer->instagram_link ?? ''}}" target="_blank"><i class="ri-instagram-line"></i></a>
                        </li>
                        <li>
                            <a href="{{$global_setting_footer->google_plus ?? ''}}" target="_blank"><i class="ri-linkedin-box-fill"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @if($footer)
    <div class="copyright">{{$footer->description ?? ''}}</div>
    @endif

</footer>
{{--<button--}}
{{--        class="termly-cookie-preference-button"--}}
{{--        type="button"--}}
{{--        style="background: white; width: 165px; height: 30px; border-radius: 3px; border: 1px solid #5f7d9c; font-family: Roboto, Arial; font-size: 10px; color: #5f7d9c; font-weight: 600; box-sizing: border-box; cursor: pointer; padding: 0; outline: none;"--}}
{{--        onclick="displayPreferenceModal()"--}}
{{-->--}}
{{--    Manage Cookie Preferences--}}
{{--</button>--}}
<button
        class="termly-cookie-preference-button"
        type="button"
        style="background: white; width: 165px; height: 30px; border-radius: 3px; border: 1px solid #5f7d9c; font-family: Roboto, Arial; font-size: 10px; color: #5f7d9c; font-weight: 600; box-sizing: border-box; cursor: pointer; padding: 0; outline: none;position:fixed;left:0;bottom:0"
        onclick="displayPreferenceModal()"
>
    Manage Cookie Preferences
</button>


<div class="modal fade gonda-modal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        @if($popup)
        <div class="modal-content" style="background-image: url({{asset('public/storage/uploads/popup/'.$popup->image ?? '')}});background-size: cover;
                                            height: 360px;
                                            width: 100%;
                                            background-position: center left;
                                            background-repeat: no-repeat;">
        @endif

            <div class="modal-header">
                @if($popup)
                    <h5 class="modal-title" id="exampleModalLabel">{{ucfirst($popup->title)}}</h5>
                @endif
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <div class="newsletter-content">
                    <div class="row">
                        <div class="col-md-6">
                            @if($popup)
                            {!! ucfirst($popup->description) !!}
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="newsletter-form">

                                <form method="post" action="{{route('marketing.add')}}" id="marketing-add-form">

                                @csrf

                                <!--  <label for="cars">Choose:</label> -->
                                    <select class="form-control" name="describe_id" id="describe_id">
                                        <option value="">What describes you best?</option>
                                        @forelse(config('gonda.marketing') as $key => $data)

                                            <option value="{{$key}}">{{$data}}</option>

                                        @empty

                                        @endforelse

                                    </select>

                                    <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email">

                                    @error('email')

                                    <span class="invalid-feedback" role="alert">

                            <strong>{{ $message }}</strong>

                            </span>

                                    @enderror

                                    <div class="newsletter-submit-btn">
                                        <button type="submit" class="btn">Sign Up</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<!-- -------footer-section--------- -->