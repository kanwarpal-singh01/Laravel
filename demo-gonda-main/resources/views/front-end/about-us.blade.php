@extends('front-end.include.layouts')
@section('content')
    <div class="inner-wraper">
        <section class="inner-banner">
            <div class="container">
                <div class="banner-head-nav">
                    <h2>About Us</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">About Us</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </section>

        @if($lorem_about)
        <section class="about-sec ok-about" >

            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        <img src="{{asset('public/storage/uploads/home-section1/'.$lorem_about->image ?? '')}}">
                    </div>
                    <div class="col-md-7">
                        <div class="about-info">
                            <h3 class="head-line-hm">{{$lorem_about->title ?? ''}}</h3>
                            {!! $lorem_about->content ?? '' !!}
{{--                            <a href="javascript:;" class="btn gradient-green" id="#section1">Read More</a>--}}
                        </div>
                    </div>
                </div>
            </div>

        </section>
        @endif

        @if($mission_about)
        <section class="mission-section">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-6 values-bg">
                        <div class="values-list">
                            <h3 class="head-line-hm">{{$mission_about->title ?? ''}}</h3>
                            {!! $mission_about->content ?? '' !!}
                        </div>
                    </div>
                    <div class="col-md-6 mission-banner">
                        <figure><img src="{{asset('public/storage/uploads/home-section1/'.$mission_about->image ?? '')}}"></figure>
                    </div>
                </div>

            </div>
        </section>
        @endif

        @if($mission_des_about)
        <section class="mission-info-secpd {{!empty($home_sec3['status']) == 1 ? 'mission-info-sec-after' : ''}}">
            <div class="container">

                <div class="mission-content">
                    <h3 class="head-line-hm">{{$mission_des_about->title ?? ''}}</h3>
                    {!! $mission_des_about->content ?? '' !!}
                </div>

            </div>
        </section>
        @endif

        @if($home_sec3)
        <section class="testimonoral-sec testimonoral-about-us">
            <div class="container">

                <div class="watch-view-bx">
                    <figure><img src="{{asset('public/front-end/images/watch-our-view.png')}}"></figure>
                    <figcaption>
                        <div class="ct-watch-text">
                            <button class="play-btn" href="javascript:;" data-toggle="modal" data-target="#myModal" ><i class="ri-play-fill"></i></button>
                            <h5>{{$home_sec3->title ?? ''}}</h5>
                        </div>
                    </figcaption>
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
                </div>

                <div class="testimonoral-here" id="testimonial2">
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



        <!-- <section class="pricing-sec">

        </section> -->



    </div>
@endsection
