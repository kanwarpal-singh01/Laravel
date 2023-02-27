@extends('layouts.site')
@section('content')

<div class="wrapper">
    <div class="bannerdiv">
        <div class="container">
            <div class="card-deck">
                <div class="col-xl-12">
                    <div class="banner_content">
                        <h3>about us</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- About Us Section Starts -->
    <div class="about-us-section section-padding section-square-morphin">
        <div class="container">
            <div class="section-heading">
                <p>{!! $home_setting->who_description_1 !!}</p>
            </div>
            <div class="card-deck mt-5">
                <div class="col-xl-6 col-sm-6">
                <div class="about-us-img">
                    <img src="{{ config('app.asset_url') }}/assets/img/story-left.png">
                </div>
                </div>
                <div class="col-xl-6 col-sm-6">
                <div class="about-us-content">
                    <h3>{!! $home_setting->who_title_2 !!}</h3>
                    <p>{!! $home_setting->who_description_2 !!} </p>
                    <!-- <button class="know-more-btn btn">know more</button> -->
                </div>
                </div>
            </div>
        </div>
    </div>
   <!-- About Us Section Ends -->

    <section class="about-us-section section-padding">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 col-md-8">
                    <div class="about-desc mr-lg-4 about-us-content">
                        <h3>{{ $about_page->title_en}}</h3>
                       {!! $about_page->meta_description_en !!}
                       {!! $about_page->content_en !!}
                        <!-- <button class="know-more-btn btn">contact us</button> -->
                    </div>
                </div>
                <div class="col-lg-5 col-md-4">
                    <img class="img-fluid rounded shadow" src="{{ config('app.asset_url') }}/assets/img/about.jpg" alt=""/>
                </div>
            </div>
        </div>
    </section>


</div>

@endsection