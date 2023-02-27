@extends('admin.include.layouts')
@section('content')
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <!-- Page body start -->
                <div class="page-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-block">
                                    <form id="global-setting-management-edit-form" method="post" action="{{route('admin.global.setting.management.edit',['id'=>base64_encode($edit->id)])}}" novalidate>
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email" value="{{$edit->email ?? ''}}">
                                                @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Facebook Link</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control @error('facebook_link') is-invalid @enderror" id="facebook_link" name="facebook_link" placeholder="Facebook Link" value="{{$edit->facebook_link ?? ''}}">
                                                @error('facebook_link')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Twitter Link</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control @error('twitter_link') is-invalid @enderror" id="twitter_link" name="twitter_link" placeholder="Twitter Link" value="{{$edit->twitter_link ?? ''}}">
                                                @error('twitter_link')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Instagram Link</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control @error('instagram_link') is-invalid @enderror" id="instagram_link" name="instagram_link" placeholder="Instagram Link" value="{{$edit->instagram_link ?? ''}}">
                                                @error('instagram_link')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">LinkedIn Link</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control @error('google_plus') is-invalid @enderror" id="google_plus" name="google_plus" placeholder="Google Plus" value="{{$edit->google_plus ?? ''}}">
                                                @error('google_plus')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Mobile Number</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control @error('mobile_number') is-invalid @enderror" id="mobile_number" name="mobile_number" placeholder="Mobile Number" value="{{$edit->mobile_number ?? ''}}">
                                                @error('mobile_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Address</label>
                                            <div class="col-sm-10">
                                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="address" rows="10" cols="80">{{$edit->address ?? ''}}</textarea>
                                                @error('address')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
{{--                                        <div class="form-group row">--}}
{{--                                            <label class="col-sm-2 col-form-label">NDA Summary</label>--}}
{{--                                            <div class="col-sm-10">--}}
{{--                                                <textarea name="nda_summary" class="form-control @error('nda_summary') is-invalid @enderror" id="nda_summary" rows="10" cols="80">{!! $edit->nda_summary ?? '' !!}</textarea>--}}
{{--                                                @error('nda_summary')--}}
{{--                                                <span class="invalid-feedback" role="alert">--}}
{{--                                                    <strong>{{ $message }}</strong>--}}
{{--                                                    </span>--}}
{{--                                                @enderror--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group row">--}}
{{--                                            <label class="col-sm-2 col-form-label">SMS Content</label>--}}
{{--                                            <div class="col-sm-10">--}}
{{--                                                <textarea name="sms_content" class="form-control @error('sms_content') is-invalid @enderror" id="sms_content" rows="10" cols="80">{{ $edit->sms_content ?? ''}}</textarea>--}}
{{--                                                @error('sms_content')--}}
{{--                                                <span class="invalid-feedback" role="alert">--}}
{{--                                                    <strong>{{ $message }}</strong>--}}
{{--                                                    </span>--}}
{{--                                                @enderror--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group row">--}}
{{--                                            <label class="col-sm-2 col-form-label">Notarize Content</label>--}}
{{--                                            <div class="col-sm-10">--}}
{{--                                                <textarea name="notarize_content" class="form-control @error('notarize_content') is-invalid @enderror" id="notarize_content" rows="10" cols="80">{{ $edit->notarize_content ?? ''}}</textarea>--}}
{{--                                                @error('notarize_content')--}}
{{--                                                <span class="invalid-feedback" role="alert">--}}
{{--                                                    <strong>{{ $message }}</strong>--}}
{{--                                                    </span>--}}
{{--                                                @enderror--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group row">--}}
{{--                                            <label class="col-sm-2 col-form-label">Notarize Amount</label>--}}
{{--                                            <div class="col-sm-10">--}}
{{--                                                <input type="text" class="form-control @error('notarize_amount') is-invalid @enderror" id="notarize_amount" name="notarize_amount" placeholder="Notarize Amount" value="{{$edit->notarize_amount ?? ''}}">--}}
{{--                                                @error('notarize_amount')--}}
{{--                                                <span class="invalid-feedback" role="alert">--}}
{{--                                                    <strong>{{ $message }}</strong>--}}
{{--                                                    </span>--}}
{{--                                                @enderror--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group row">--}}
{{--                                            <label class="col-sm-2 col-form-label">Stripe Detail</label>--}}
{{--                                            <div class="col-sm-10">--}}
{{--                                                <input type="text" class="form-control @error('stripe_detail') is-invalid @enderror" id="stripe_detail" name="stripe_detail" placeholder="Stripe Detail" value="{{$edit->stripe_detail ?? ''}}">--}}
{{--                                                @error('stripe_detail')--}}
{{--                                                <span class="invalid-feedback" role="alert">--}}
{{--                                                    <strong>{{ $message }}</strong>--}}
{{--                                                    </span>--}}
{{--                                                @enderror--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Show Map</label>
                                            <div class="form-radio">
                                                <div class="col-sm-5 radio radio-outline radio-inline">
                                                    <label>
                                                        <input type="radio" name="map" id="map-active" value="1" {{$edit->map === 1 ? 'checked' : ''}}>
                                                        <i class="helper"></i>Active
                                                    </label>
                                                </div>
                                                <div class="col-sm-6 radio radio-outline radio-inline">
                                                    <label>
                                                        <input type="radio" name="map" id="map-inactive" value="0" {{$edit->map === 0 ? 'checked' : ''}}>
                                                        <i class="helper"></i>Inactive
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Show Enquiry</label>
                                            <div class="form-radio">
                                                <div class="col-sm-5 radio radio-outline radio-inline">
                                                    <label>
                                                        <input type="radio" name="enquiry" id="enquiry-active" value="1" {{$edit->enquiry === 1 ? 'checked' : ''}}>
                                                        <i class="helper"></i>Active
                                                    </label>
                                                </div>
                                                <div class="col-sm-6 radio radio-outline radio-inline">
                                                    <label>
                                                        <input type="radio" name="enquiry" id="enquiry-inactive" value="0" {{$edit->enquiry === 0 ? 'checked' : ''}}>
                                                        <i class="helper"></i>Inactive
                                                    </label>
                                                </div>
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

        //CKEDITOR.replace( 'nda_summary' );
        CKEDITOR.replace('nda_summary', {
            height: 250,
            extraPlugins: 'colorbutton'
        });
        CKEDITOR.instances['nda_summary'].getData();
        CKEDITOR.config.allowedContent = true;
    </script>
@endsection

