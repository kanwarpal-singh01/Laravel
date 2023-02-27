@extends('front-end.include.layouts')

@section('content')

    <div class="site-wraper">

        @if($banner)
        <section class="home-banner">

            <div class="container home-banner-in">

                <div class="row">

                    <div class="col-md-6">

                        <div class="home-text-left">

                            <h2>{{$banner->title ?? ''}}</h2>

                            {!! $banner->content ?? '' !!}

                            @if($footer)

                            <div class="stores-btns">

                                <a href="#" class="btn btn-green" data-toggle="{{!empty($popup['status']) ? 'modal' : ''}}" data-target="{{!empty($popup['status']) ? '#exampleModalCenter' : ''}}" id="app-store-id"><i class="ri-app-store-fill"></i> App Store</a>

                                <a href="#" class="btn btn-yellow" data-toggle="{{!empty($popup['status']) ? 'modal' : ''}}" data-target="{{!empty($popup['status']) ? '#exampleModalCenter' : ''}}" id="google-pay-id"><i class="ri-google-play-fill"></i> Google Play</a>

                            </div>

                                @endif

                        </div>

                    </div>

                    <div class="col-md-6 frame-flex">

                        <div class="home-device">

                            <figure><img src="{{asset('public/storage/uploads/banner/'.$banner->image ?? '')}}"></figure>

                        </div>

                    </div>

                </div>
            </div>

            <div class="scroll-down-out">

                @if($global_setting_footer)

                <div class="scroll-down-box">

{{--                    <a class="scroll-down-here scroll-down" href="javascript:;"> Scroll Down <i class="ri-arrow-down-line"></i></a>--}}
                    <a class="scroll-down-here scroll-down" href="javascript:;"><i class="ri-arrow-down-line"></i></a>
                    <a class="scroll-down-here scroll-top" href="javascript:;"><i class="ri-arrow-down-line"></i></a>

                    <ul>

                        <li><a href="{{$global_setting_footer->instagram_link ?? ''}}" target="_blank"><i class="ri-instagram-line"></i></a></li>

                        <li><a href="{{$global_setting_footer->twitter_link ?? ''}}" target="_blank"><i class="ri-twitter-fill"></i></a></li>

{{--                        <li><a href="{{$global_setting_footer->google_plus ?? ''}}"><i class="ri-youtube-fill"></i></a></li>--}}

                        <li><a href="{{$global_setting_footer->facebook_link ?? ''}}" target="_blank"><i class="ri-facebook-box-fill"></i></a></li>
                        <li>
                            <a href="{{$global_setting_footer->google_plus ?? ''}}" target="_blank"><i class="ri-linkedin-box-fill"></i></a>
                        </li>

                    </ul>

                </div>

                @endif

            </div>

        </section>
        @endif

        @if($home_sec1)
         <section class="about-sec  ok-about" >

            <div class="container">

                <div class="row">

                    <div class="col-md-5">

                        <img src="{{asset('public/storage/uploads/home-section1/'.$home_sec1->image ?? '')}}">

                    </div>

                    <div class="col-md-7">

                        <div class="about-info">

                            <h3 class="head-line-hm">{{$home_sec1->title ?? ''}}</h3>

                            {!! $home_sec1->content ?? '' !!}

                            <a href="{{route('about.us')}}" class="btn gradient-green" id="#section1">About us</a>

                        </div>

                    </div>

                </div>

            </div>

        </section>
        @endif

        @if(count($home_sec2)>0)
         <section class="get-surprise-sec" id="features">

             <div class="container">

                <h3 class="head-line-hm">{{$home_sec2[0]->title ?? ''}}</h3>

                <ul class="row list-features">

                    @forelse($home_sec2 as $data)

                    <li class="col-md-3">

                        <a href="javascript:;">

                            <figure><i class="{{$data->icon ?? ''}}"></i></figure>

                            <figcaption>

                                {!! $data->content ?? '' !!}

                            </figcaption>

                        </a>

                    </li>

                    @empty

                    @endforelse

                </ul>

            </div>

        </section>
        @endif

        @if($home_sec3)
        <section class="testimonoral-sec">

            <div class="container">



                <div class="watch-view-bx">

                    <figure><img src="{{asset('public/front-end/images/watch-our-view.png')}}"></figure>

                    <figcaption>

                        <div class="ct-watch-text">

                            <button class="play-btn" href="javascript:;" data-toggle="modal" data-target="#myModal" ><i class="ri-play-fill"></i></button>

                            <h5>{{$home_sec3->title ?? ''}}</h5>

                        </div>

                    </figcaption>

                </div>

                <div class="modal fade browser-video" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

                        <div class="modal-dialog modal-dialog-centered" role="document">

                            <div class="modal-content">

                                <div class="modal-header">

                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                                    <h4 class="modal-title" id="myModalLabel"></h4>

                                </div>

                                <div class="modal-body">

                                    <video controls id="video1">

                                        <source src="{{asset('public/storage/uploads/home-section3/'.$home_sec3->video)}}" type="video/mp4">

                                        Your browser doesn't support HTML5 video tag.

                                    </video>

                                </div>

                            </div>

                        </div>

                    </div>



                <div class="testimonoral-here" id="testimonial">

                    @if(count($testimonial)>0)

                    <h3 class="head-line-hm">Hear from what other people had to say.</h3>

                    <div class="slider-arrow people-say owl-carousel owl-theme">

                        @forelse($testimonial as $testimonial_data)

                        <div class="item">

                            <div class="people-box">

                                <div class="text-say-info">

                                    {!! $testimonial_data->description ?? '' !!}

                                </div>

                                <div class="people-user">

                                    <figure><img src="{{asset('public/storage/uploads/testimonial/'.$testimonial_data->image ?? '')}}"></figure>

                                    <figcaption>

                                        <h4>{{ucfirst($testimonial_data->first_name .' '.$testimonial_data->last_name ?? '')}}</h4>

                                        <h6>{{ucfirst($testimonial_data->designation ?? '')}}</h6>

                                    </figcaption>

                                </div>

                            </div>

                        </div>

                        @empty

                        @endforelse

                    </div>

                    @endif

                </div>

            </div>

        </section>
        @endif

        @if(count($home_sec5)>0)
        <section class="look-app-sec" id="screenshot">

            <div class="testimonoral-here">



                <div class="container">

                    <h3 class="head-line-hm">{{$home_sec5[0]->title ?? ''}}</h3>

                </div>

                <div class="green-arrow slider-arrow inside-app owl-carousel owl-theme">

                    @forelse($home_sec5 as $data)

                    <div class="item">

                        <div class="look-app-box">

                            <figure><img src="{{asset('public/storage/uploads/home-section5/'.$data->image ?? '')}}"></figure>

                        </div>

                    </div>

                    @empty

                    @endforelse

                </div>



            </div>

        </section>
        @endif

        @if(count($home_sec6)>0)
        <section class="" id="plan">

{{--            <div class="container">--}}

{{--                <h3 class="head-line-hm">We've made sure our pricing is affordable.</h3>--}}

{{--                <div class="row plans-list-bx">--}}

{{--                    @forelse($home_sec6 as $data)--}}

{{--                    <div class="col-md-3">--}}

{{--                        <article>--}}

{{--                            <h3>{{$data->title}}</h3>--}}

{{--                            <h2>${{$data->price}}</h2>--}}

{{--                            <h6>{{$data->credit_points}} {{$data->name}}</h6>--}}

{{--                            <p>{{$data->content}}</p>--}}

{{--                            <a href="javascript:;" class="btn gradient-green">Buy now</a>--}}

{{--                        </article>--}}

{{--                    </div>--}}
{{--                    @empty--}}
{{--                    @endforelse--}}
{{--                </div>--}}

{{--            </div> --}}


            <!-- -------new plan setion------ -->
            <div class="container">
                                <h3 class="head-line-hm">We've made sure our pricing is affordable.</h3>
                    <div class="row plans-sec-flex">
                        @forelse($home_sec6 as $data)
                        <div class="col-md-3">
                            <div class="plans-sec-bx">
                                <div class="main-plan">
                                    <h2>{{$data->title}}</h2>
                                </div>
                                <figcaption>
                                    <h3>${{$data->price}}</h3>
                                    <h4>{{$data->credit_points}} {{$data->name}}</h4>
                                    <p>{{$data->content}}</p>

                                </figcaption>
                                <!-- <div class="buy-btn-sec"><a href="javascript:;" class="btn gradient-green">Buy now</a></div> -->
                            </div>
                        </div>
                            @empty
                            @endforelse
                    </div>
            </div>
            <!-- -------new plan setion------ -->




        </section>
        @endif

    </div>
@endsection

{{--<div class="modal fade gonda-modal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">--}}

{{--  <div class="modal-dialog modal-dialog-centered" role="document">--}}

{{--    <div class="modal-content">--}}

{{--      <div class="modal-header">--}}

{{--        <h5 class="modal-title" id="exampleModalLabel">Newsletter</h5>--}}

{{--        <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}

{{--          <span aria-hidden="true">&times;</span>--}}

{{--        </button>--}}

{{--      </div>--}}

{{--      <div class="modal-body">--}}

{{--       <div class="newsletter-content">--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-6">--}}
{{--                      <h5>Lorem Ipsum is simply dummy text.</h5>--}}
{{--                      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text</p>--}}
{{--                </div>--}}
{{--                <div class="col-md-6">--}}
{{--                    <div class="newsletter-form">--}}

{{--                       <form method="post" action="{{route('marketing.add')}}" id="marketing-add-form">--}}

{{--                           @csrf--}}

{{--                          <!--  <label for="cars">Choose:</label> -->--}}
{{--                           <select class="form-control" name="describe_id" id="describe_id">--}}
{{--                               <option value="">What describes you best?</option>--}}
{{--                               @forelse(config('gonda.marketing') as $key => $data)--}}

{{--                               <option value="{{$key}}">{{$data}}</option>--}}

{{--                                   @empty--}}

{{--                               @endforelse--}}

{{--                           </select>--}}

{{--                           <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email">--}}

{{--                           @error('email')--}}

{{--                           <span class="invalid-feedback" role="alert">--}}

{{--                            <strong>{{ $message }}</strong>--}}

{{--                            </span>--}}

{{--                           @enderror--}}

{{--                           <div class="newsletter-submit-btn">--}}
{{--                                 <button type="submit" class="btn">Sign Up</button>--}}
{{--                           </div>--}}

{{--                       </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--       </div>--}}

{{--      </div>--}}

{{--    </div>--}}

{{--  </div>--}}

{{--</div>--}}

@section('scripts')

<script src={{ asset('public/challenge-validation/validation.js') }}></script>

@endsection