@extends('front-end.include.layouts')
@section('content')
    <div class="inner-wraper">
        <section class="inner-banner">
            <div class="container">
                @if($terms_conditions)
                <div class="banner-head-nav">
                    <h2>{{$terms_conditions->title ?? ''}}</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{$terms_conditions->title ?? ''}}</li>
                        </ol>
                    </nav>
                </div>
                    @endif
            </div>
        </section>

        <section class="contact-section-pdd">
            <div class="container">
                @if($terms_conditions)
                <div class="privacy-trems-text">
                    {!! $terms_conditions->content ?? '' !!}
                </div>
                    @endif
                </div>
        </section>

    </div>
@endsection
