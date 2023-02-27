@extends('layouts.site')
@section('content')
<div class="wrapper">
    <!-- Banner Section Starts -->
    <div class="banner-slider owl-slider">
        <div id="banner_slide" class="owl-carousel">
            <div class="item">
                <img src="{{ config('app.asset_url') }}/assets/img/banner-bg.png" alt="" />
                <div class="container">
                    <div class="card-deck">
                        <div class="banner-content">
                            <div class="banner-c-text">
                                <h3>{!! $home_setting->slider_title_1 !!}</h3>
                                <p>{!! $home_setting->slider_description_1 !!}</p>
                                <div class="banner-btn">
                                    <!-- <button class="btn">Know More</button> -->
                                    <a href="contact-us" class="btn btn-orange">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="{{ config('app.asset_url') }}/assets/img/banner-bg.png" alt="" />
                <div class="container">
                    <div class="card-deck">
                        <div class="banner-content">
                            <div class="banner-c-text">
                                <h3>{!! $home_setting->slider_title_2 !!}</h3>
                                <p>{!! $home_setting->slider_description_2 !!}</p>
                                <div class="banner-btn">
                                    <!-- <button class="btn">Know More</button> -->
                                    <a href="contact-us" class="btn btn-orange">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="{{ config('app.asset_url') }}/assets/img/banner-bg.png" alt="" />
                <div class="container">
                    <div class="card-deck">
                        <div class="banner-content">
                            <div class="banner-c-text">
                                <h3>{!! $home_setting->slider_title_3 !!}</h3>
                                <p>{!! $home_setting->slider_description_3 !!}</p>
                                <div class="banner-btn">
                                    <!-- <button class="btn">Know More</button> -->
                                    <a href="contact-us" class="btn btn-orange">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner Section Ends -->
    <!-- About Us Section Starts -->
    <div class="about-us-section section-padding section-square-morphin">
        <div class="container">
            <div class="section-heading">
                <span class="top-small-text">who we are</span>
                <h3 class="section-h-title">{!! $home_setting->who_title_1 !!}</h3>
                <p>{!! $home_setting->who_description_1 !!}</p>
            </div>
            <div class="card-deck">
                <div class="col-xl-6 col-sm-6">
                    <div class="about-us-img">
                        <img src="{{ config('app.asset_url') }}/assets/img/story-left.png">
                    </div>
                </div>
                <div class="col-xl-6 col-sm-6">
                    <div class="about-us-content">
                        <h3>{!! $home_setting->who_title_2 !!}</h3>
                        <p>{!! $home_setting->who_description_2 !!}</p>
                        <!-- <button class="know-more-btn btn">know more</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About Us Section Ends -->
    <!-- How It Works Section Starts -->
    <div class="how-it-works section-padding section-square-morphin">
        <div class="container">
            <div class="section-heading">
                <h3 class="section-h-title">How it works</h3>
                <!-- <p>There is a powerful need for symbolism, and that means the architecture must have something that appeals to the human heart.</p> -->
            </div>
            <ul class="work-steps">
                <li>
                    <figure>
                        <img src="{{ config('app.asset_url') }}/assets/img/works-1.png">
                    </figure>
                    <h5>{!! $home_setting->how_title_1 !!}</h5>
                    <p>{!! $home_setting->how_description_1 !!}</p>
                </li>
                <li>
                    <figure>
                        <img src="{{ config('app.asset_url') }}/assets/img/works-2.png">
                    </figure>
                    <h5>{!! $home_setting->how_title_2 !!}</h5>
                    <p>{!! $home_setting->how_description_2 !!}</p>
                </li>
                <li>
                    <figure>
                        <img src="{{ config('app.asset_url') }}/assets/img/works-1.png">
                    </figure>
                    <h5>{!! $home_setting->how_title_3 !!}</h5>
                    <p>{!! $home_setting->how_description_3 !!}</p>
                </li>
                <li>
                    <figure>
                        <img src="{{ config('app.asset_url') }}/assets/img/works-2.png">
                    </figure>
                    <h5>{!! $home_setting->how_title_4 !!}</h5>
                    <p>{!! $home_setting->how_description_4 !!}</p>
                </li>
            </ul>
            <!-- <button class="know-more-btn btn">know more</button> -->
        </div>
    </div>
    <!-- How It Works Section Ends -->
    <!-- Our Top Client Section Starts -->
    <div class="our-client-section section-padding section-square-morphin">
        <div class="section-heading">
            <h3 class="section-h-title">OUR TOP Recruiters</h3>
            <p>There is a powerful need for symbolism, and that means the architecture must have something that appeals
                to the human heart.</p>
        </div>
        <div class="container">
            <ul class="our-client-list">


                @foreach($recruiters as $recruiter)

                <li>
                    <figure>
                        <img src="{{ config('app.asset_url') }}/assets/img/{{$recruiter->profile_image}}">
                    </figure>
                    <div class="client-text">
                        <h5>{{ucfirst($recruiter->user_name)}}</h5>
                        <p>{{ $recruiter->agency_description }}</p>
                        <?php

$rating=(int)round(Helper::get_user_rating($recruiter->id));
?>


                        <div class="rating-span">
                            @for($i=0;$i<$rating;$i++) <span><i class="fas fa-star"></i></span>
                                @endfor

                                @for($i=0;$i<(5-$rating);$i++) <span><i class="far fa-star"></i></span>
                                    @endfor




                        </div>
                        <a href="{{ url('/sign-in')}}" class="btn">Know More</a>
                    </div>
                </li>

                @endforeach

            </ul>
            <!-- <button class="know-more-btn btn">know more</button> -->
        </div>
    </div>
    <!-- Our Top Client Section Ends -->
    <!-- Our Top Companies Section Starts -->
    <div class="our-companies-section section-padding section-square-morphin">
        <div class="section-heading">
            <h3 class="section-h-title">OUR TOP EMPLOYERS</h3>
            <p>There is a powerful need for symbolism, and that means the architecture must have something that appeals
                to the human heart.</p>
        </div>
        <div class="container">
            <div class="card-deck">
                <div class="col-xl-6 col-md-6">
                    <ul class="our-comp-img">
                        <li class="bottom-border-fadded-left"><img
                                src="{{ config('app.asset_url')}}/assets/img/our-client-1.png"></li>
                        <li><img src="{{ config('app.asset_url')}}/assets/img/our-client-2.png"></li>
                        <li><img src="{{ config('app.asset_url')}}/assets/img/our-client-3.png"></li>
                        <li class="no-right-border bottom-border-fadded-right"><img
                                src="{{ config('app.asset_url')}}assets/img/our-client-4.png"></li>
                        <li class="bottom-border-fadded-left"><img
                                src="{{ config('app.asset_url')}}/assets/img/our-client-5.png"></li>
                        <li><img src="{{ config('app.asset_url')}}/assets/img/our-client-6.png"></li>
                        <li><img src="{{ config('app.asset_url')}}/assets/img/our-client-7.png"></li>
                        <li class="no-right-border bottom-border-fadded-right"><img
                                src="{{ config('app.asset_url')}}/assets/img/our-client-8.png"></li>
                        <li class="bottom-border-fadded-left"><img
                                src="{{ config('app.asset_url')}}/assets/img/our-client-9.png"></li>
                        <li><img src="{{ config('app.asset_url')}}/assets/img/our-client-10.png"></li>
                        <li><img src="{{ config('app.asset_url')}}/assets/img/our-client-11.png"></li>
                        <li class="no-right-border bottom-border-fadded-right"><img
                                src="{{ config('app.asset_url')}}/assets/img/our-client-3.png"></li>
                        <li class="no-bottom-border"><img
                                src="{{ config('app.asset_url')}}/assets/img/our-client-6.png"></li>
                        <li class="no-bottom-border"><img
                                src="{{ config('app.asset_url')}}/assets/img/our-client-1.png"></li>
                        <li class="no-bottom-border"><img
                                src="{{ config('app.asset_url')}}/assets/img/our-client-8.png"></li>
                        <li class="no-right-border no-bottom-border"><img
                                src="{{ config('app.asset_url')}}/assets/img/our-client-2.png"></li>
                    </ul>
                </div>
                <div class="col-xl-6 col-md-6">
                    <div class="our-comp-content about-us-content">
                        <h3>Sed ut perspiciatis unde omnis iste natus error sit</h3>
                        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor
                            invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam
                            et justo duo dolores et ea rebum. </p>
                        <!-- <button class="know-more-btn btn">know more</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Our Top Companies Section Ends -->
    <!-- FAQ Section Starts -->
    <div class="faq-section section-padding">
        <div class="section-heading">
            <h3 class="section-h-title">frequently asked questions</h3>
            <p>There is a powerful need for symbolism, and that means the architecture must have something that appeals
                to the human heart.</p>
        </div>
        <div class="container">
            <div class="card-deck">
                <div class="col-xl-6 col-lg-5 col-md-5">
                    <div class="faq-img">
                        <img src="{{ config('app.asset_url') }}/assets/img/faq-left-img.png">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-7 col-md-7">
                    <div class="faq-acc accordion" id="accordionFaq">


                        @foreach($faqs as $faq)
                        <div class="card">
                            <div class="card-header" id="heading{{ $faq->id}}">
                                <h2 class="mb-0">
                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                        data-target="#collapse{{ $faq->id}}" aria-expanded="true"
                                        aria-controls="collapseOne">
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse{{ $faq->id}}" class="collapse" aria-labelledby="heading{{ $faq->id}}"
                                data-parent="#accordionFaq">
                                <div class="card-body">
                                    {{ $faq->answer }}
                                </div>
                            </div>
                        </div>
                        @endforeach





                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FAQ Section Ends -->
    <!-- Contact Us Section Starts -->
    <div class="contact-us-section section-padding section-square-morphin">
        <div class="section-heading">
            <h3 class="section-h-title">Get in touch with us</h3>
            <p>There is a powerful need for symbolism, and that means the architecture must have something that appeals
                to the human heart.</p>
        </div>
        <div class="container">
            <div class="card-deck">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <div class="contact-form">
                        @include('includes.flashMessage')
                        <form action="{{ url('/') }}/save-contact" method="post" id="contact-us-form">
                            {{ csrf_field() }}
                            <div class="card-deck">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="First Name"
                                            name="first_name" />
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Last Name"
                                            name="last_name" />
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Phone Number"
                                            name="phone" />
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="You Email Address"
                                            name="email" />
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <textarea type="text" class="form-control" placeholder="Type Your Message"
                                            name="message"></textarea>
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <button type="submit" class="btn know-more-btn">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <div class="contact-img">
                        <img src="{{ config('app.asset_url') }}/assets/img/contact-us-img.png">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact Us Section Ends -->
</div>
</div>



<script>
// just for the demos, avoids form submit
$(function() {

    jQuery.validator.addMethod("special", function(value, element) {
        return this.optional(element) || /^[a-z]+$/i.test(value);
    }, "Special character and Number not allowed");

    jQuery.validator.addMethod("no_space", function(value, element) {
        return this.optional(element) || /^\S*$/i.test(value);
    }, "Space not allowed");

    jQuery.validator.addMethod("not_same", function(value, element) {
        return this.optional(element) || /^(?=.{8,20}$)(([a-z0-9])\2?(?!\2))+$/i.test(value);
    }, "Not same character allowed");

    jQuery.validator.addMethod("let_space", function(value, element) {
        return this.optional(element) || /^[a-zA-Z_ ]*$/i.test(value);
    }, "Letters and space allowed only ");





    jQuery.validator.addMethod("no_space_beg", function(value, element) {
        return this.optional(element) || /^[^\s]+(\s.*)?$/i.test(value);
    }, "No space allowed in starting");


    jQuery.validator.addMethod("all_zero", function(value, element) {
        return this.optional(element) || /\d{3}[1-9]/i.test(value);
    }, "All number cannot Zero");






    jQuery.validator.setDefaults({
        debug: true,
        success: "valid"

    });
    $("#contact-us-form").validate({
        submitHandler: function(form) {
            grecaptcha.ready(function() {
                grecaptcha.execute('6LePicMZAAAAAC1ta45ya3GCurKvagU-nDW_xqJa', {
                    action: 'submit'
                }).then(function(token) {
                    form.submit();
                });
            });

        },

        rules: {
            first_name: {
                required: true,
                minlength: 3,
                maxlength: 55,
                let_space: true,
                no_space_beg: true,
            },

            last_name: {
                required: true,
                minlength: 3,
                maxlength: 55,
                let_space: true,
                no_space_beg: true,
            },


            message: {
                required: true,
                minlength: 10,
                maxlength: 400,
            },
            email: {
                required: true,
                email: true,
                maxlength: 30,
            },
            phone: {
                required: true,
                digits: true,
                minlength: 7,
                maxlength: 15,
                all_zero: true,

            },



        },

    });


});
</script>

@endsection