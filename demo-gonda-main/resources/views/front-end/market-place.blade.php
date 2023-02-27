@extends('front-end.include.layouts')
@section('content')
    <div class="inner-wraper">
        <section class="inner-banner">
            <div class="container">
                <div class="banner-head-nav">
                    <h2>{{$market_place->title}}</h2>
                    {{--                    <nav aria-label="breadcrumb">--}}
                    {{--                        <ol class="breadcrumb">--}}
                    {{--                            <li class="breadcrumb-item"><a href="#">Home</a></li>--}}
                    {{--                            <li class="breadcrumb-item active" aria-current="page">{{$doc->title}}</li>--}}
                    {{--                        </ol>--}}
                    {{--                    </nav>--}}
                </div>
            </div>
        </section>
        <section class="about-sec  ok-about-pdd">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="about-info">

                            <div class="legal-sec">
                                <figure><img src="http://gonda.devtechnosys.info/public/front-end/images/legal-img.svg"></figure>
                                <figcaption>
                                    <h6>Legal Marketplace</h6>
                                    <h3>COMING SOON!</h3>
                                </figcaption>
                            </div>

                            <!-- <h3 class="head-line-hm">{{$market_place->title}}</h3>

                            {!! $market_place->description !!} -->

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection
