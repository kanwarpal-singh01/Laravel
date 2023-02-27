@extends('admin.include.layouts')
@section('content')
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="form-group row">
                    <div class="col-md-12" style="margin-right: 873px;">
                        <a class="btn waves-effect waves-light btn-grd-primary" href="{{url('Kobe/contact-us/list?page='.request('page'))}}">
                            <i class="fa fa-backward"></i>Back
                        </a>
                    </div>
                </div>
                <!-- Page body start -->
                <div class="page-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-block">
                                    <form id="contact-reply-form" method="post" action="{{route('admin.contact.reply',['id'=>base64_encode($contact->id), 'page'=>request('page')])}}" novalidate>
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">To</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="To Email" value="{{$contact->email ?? ''}}">
                                                @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">From</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control @error('from_email') is-invalid @enderror" id="from_email" name="from_email" placeholder="From Email" value="gonda.devtech@gmail.com" readonly>
                                                @error('from_email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Subject</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" placeholder="Subject" value="{{old('subject')}}">
                                                @error('subject')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Message</label>
                                            <div class="col-sm-10">
                                                <textarea name="message" class="form-control @error('message') is-invalid @enderror" id="message" rows="10" cols="80">{{old('message')}}</textarea>
                                                @error('message')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2"></label>
                                            <div class="col-sm-10">
                                                <button class="btn waves-effect waves-light btn-grd-primary">Submit
                                                    <i class="fa fa-refresh fa-spin" style="display: none" id="spinner"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src={{ asset('public/challenge-validation/validation.js') }}></script>
    <script>
      CKEDITOR.replace( 'message' );
    </script>
@endsection
