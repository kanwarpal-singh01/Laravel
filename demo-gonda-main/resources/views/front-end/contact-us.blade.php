@extends('front-end.include.layouts')
@section('content')
    <div class="inner-wraper">
        <section class="inner-banner">
            <div class="container">
                <div class="banner-head-nav">
                    <h2>Contact Us</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </section>
        <div class="got-questions pt-5">
                     <h3 class="head-line-hm">We'd love to hear from you.</h3>
                    <script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/shell.js"></script>
                     <script>
                        hbspt.forms.create({
                            region: "na1",
                            portalId: "20039590",
                            formId: "2b378fba-b9f7-4ed5-a593-6335321fcffc"
                        });
                    </script>
                 </div>
        <section class="contact-section-pdd pt-1">
            <div class="container">
                <div class="click-help pb-3">
                    <h3 class="head-line-hm">Other ways to get in touch.</h3>
                    <ul class="click-social">
                        <li>
                            <a href="javascript:;">
                                <figure><img src="{{asset('public/front-end/images/phone-icon.svg')}}"></figure>
                                <figcaption>
                                    {{--                                    <h6>Please call on</h6>--}}
                                    <h4>{{$global_setting->mobile_number ?? ''}}</h4>
                                    {{--                                    <h6>to get support.</h6>--}}
                                </figcaption>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:;">
                                <figure><img src="{{asset('public/front-end/images/info-icon.svg')}}"></figure>
                                <figcaption>
                                    {{--                                    <h6>Please email on</h6>--}}
                                    <h4>{{$global_setting->email ?? ''}}</h4>
                                    {{--                                    <h6>to get support.</h6>--}}
                                </figcaption>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:;">
                                <figure><img src="{{asset('public/front-end/images/location-icon.svg')}}"></figure>
                                <figcaption>
                                    <h4>{{$global_setting->address ?? ''}}</h4>
                                </figcaption>
                            </a>
                        </li>
                    </ul>
                </div>
                @if($global_setting->map === 1)
                    <div class="innre-maps">
                        <div class="maps-iframe">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3147.9785834072295!2d144.65758121509572!3d-37.907563279735456!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad685aedba246a3%3A0xfdcfa2090963294c!2sKing%20St%2C%20Werribee%20VIC%203030%2C%20Australia!5e0!3m2!1sen!2sin!4v1619775297037!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                            <div class="visit-click-bx">
                                <h5>Coming for a visit or a coffee? </h5>
                                <a href="javascript:;"> <span>Get Direction</span> <i class="ri-arrow-right-s-line"></i></a>
                            </div>
                        </div>
                    </div>
                @endif
                @if($global_setting->enquiry === 1)
                    <div class="got-questions">
                        <h3 class="head-line-hm">Got Questions? Write us a message.</h3>
                        <form action="{{route('contact.us.add')}}" method="post" id="front-end-contact">
                            @csrf
                            <div class="form-row">got-questions
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" placeholder="First Name*" name="first_name" value="{{old('first_name')}}">
                                    @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" placeholder="Last Name*" name="last_name" value="{{old('last_name')}}">
                                    @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Email Address*" name="email" value="{{old('email')}}">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control @error('mobile_number') is-invalid @enderror" placeholder="Phone Number*" name="mobile_number" value="{{old('mobile_number')}}">
                                    @error('mobile_number')
                                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <textarea type="text" class="form-control @error('message') is-invalid @enderror" placeholder="Message*" name="message">{{old('message')}}</textarea>
                                    @error('message')
                                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="col-md-12 text-center pt-3">
                                    <button type="submit" class="btn gradient-green">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection





