@extends('front-end.include.layouts')
@section('content')
    <div class="inner-wraper">
        <section class="inner-banner">
            <div class="container">
                <div class="banner-head-nav">
                    <h2>Knowledge Center</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Knowledge Center</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </section>

        <section class="contact-section-pdd">
            @if($knowledge)
            <div class="container">
                <div class="knw-center">
                    <div class="row justify-content-center">
                        @foreach($knowledge as $data)
                        <div class="col-md-4">
                            <article>
                                @if($data->description)
                                <figure>
                                    <a href="{{route('document.page',['id'=>base64_encode($data->id)])}}" id="all-scroll">
                                        <img src="{{asset('public/front-end/images/file-icon.png')}}">
                                    </a>
                                </figure>
                                <h4>{{$data->title}}</h4>
                                @else
                                    <figure>
                                        <img src="{{asset('public/front-end/images/file-icon.png')}}">
                                    </figure>
                                    <h4>{{$data->title}}</h4>
                                    <span>COMING SOON</span>
                                @endif
                            </article>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </section>
    </div>
@endsection
