@extends('front-end.include.layouts')
@section('content')
    <div class="inner-wraper">
        <section class="inner-banner">
            <div class="container">
                <div class="banner-head-nav">
                    <h2>{{$doc->title}}</h2>
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
{{--                            <h3 class="head-line-hm">{{$doc->title}}</h3>--}}
                            {!! $doc->description !!}
{{--                            @if($doc->document)--}}
{{--                            <a href="{{asset('public/storage/uploads/knowledge-center/'.$doc->document)}}" class="btn gradient-green" id="#section1" target="_blank">View PDF</a>--}}
{{--                            @endif--}}
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection
