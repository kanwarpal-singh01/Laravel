@extends('front-end.include.layouts')
@section('content')
    <div class="inner-wraper">
        <section class="inner-banner">
            <div class="container">
                @if($privacy_policy)
                <div class="banner-head-nav">
                    <h2>{{$privacy_policy->title ?? ''}}</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>

                            <li class="breadcrumb-item active" aria-current="page">{{$privacy_policy->title ?? ''}}</li>

                        </ol>
                    </nav>
                </div>
                @endif
            </div>
        </section>

        <section class="contact-section-pdd">
            <div class="container">
                @if($privacy_policy)
                <div class="privacy-trems-text">
                    {!! $privacy_policy->content ?? '' !!}
                </div>
                @endif
            </div>
        </section>

    </div>
@endsection
