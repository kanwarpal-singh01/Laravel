@extends('admin.include.layouts')
@section('content')
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-block">
                                    <form id="profile-update-form" method="post" action="{{route('admin.user.view',['id'=>base64_encode($view->id), 'page'=>request('page')])}}" novalidate>
                                        @csrf
                                        <div class="form-group row" style="margin: -12px">
                                            <label class="col-sm-2 col-form-label">First Name</label>
                                            <div class="col-sm-10">
                                                <label>{{$view->first_name ?? 'No first name'}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="margin: -12px">
                                            <label class="col-sm-2 col-form-label">Last Name</label>
                                            <div class="col-sm-10">
                                                <label>{{$view->last_name ?? 'No last name'}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="margin: -12px">
                                            <label class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <label>{{$view->email ?? 'No email'}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="margin: -12px">
                                            <label class="col-sm-2 col-form-label">Mobile Number</label>
                                            <div class="col-sm-10">
                                                <label>{{$view->mobile_number ?? 'No mobile number'}}</label>
                                            </div>
                                        </div>
{{--                                        <div class="form-group row" style="margin: -12px">--}}
{{--                                            <label class="col-sm-2 col-form-label">Image</label>--}}
{{--                                            <div class="col-sm-10">--}}
{{--                                                @if($view->image)--}}
{{--                                                    <label><img src="{{asset('public/storage/uploads/user-profile/'.$view->image ?? '')}}" style="width: 20px; height: 20px"></label>--}}
{{--                                                @else--}}
{{--                                                    <label>No Image</label>--}}
{{--                                                @endif--}}

{{--                                            </div>--}}
{{--                                        </div>--}}
                                        <div class="form-group row" style="margin: -12px">
                                            <label class="col-sm-2 col-form-label">Status</label>
                                            <div class="col-sm-10">
                                                <label>{{$view->status ? 'Active' : 'Inactive'}}</label>
                                            </div>
                                        </div>

{{--                                        <div class="form-group row" style="margin: -12px">--}}
{{--                                            <label class="col-sm-2 col-form-label">Secret Pin</label>--}}
{{--                                            <div class="col-sm-10">--}}
{{--                                                <label>{{$view->secret_pin ?? 'No secret pin'}}</label>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

                                        <div class="form-group row" style="margin: -12px">
                                            <label class="col-sm-2 col-form-label">Company Name</label>
                                            <div class="col-sm-10">
                                                <label>{{$view->companyDetail->company_name ?? 'No company name'}}</label>
                                            </div>
                                        </div>

                                        <div class="form-group row" style="margin: -12px">
                                            <label class="col-sm-2 col-form-label">Job Title</label>
                                            <div class="col-sm-10">
                                                <label>{{$view->companyDetail->job_title ?? 'No job title'}}</label>
                                            </div>
                                        </div>

{{--                                        <div class="form-group row" style="margin: -12px">--}}
{{--                                            <label class="col-sm-2 col-form-label">Street Address</label>--}}
{{--                                            <div class="col-sm-10">--}}
{{--                                                <label>{{$data->mailingAddress->street_address ?? 'No street address'}}</label>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

{{--                                        <div class="form-group row" style="margin: -12px">--}}
{{--                                            <label class="col-sm-2 col-form-label">Apartment Number</label>--}}
{{--                                            <div class="col-sm-10">--}}
{{--                                                <label>{{$data->mailingAddress->apartment_number ?? 'No apartment number'}}</label>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

                                        <div class="form-group row" style="margin: -12px">
                                            <label class="col-sm-2 col-form-label">Country Name</label>
                                            <div class="col-sm-10">
                                                <label>{{$view->mailingAddress->countries->name ?? 'No country name'}}</label>
                                            </div>
                                        </div>

                                        <div class="form-group row" style="margin: -12px">
                                            <label class="col-sm-2 col-form-label">State Name</label>
                                            <div class="col-sm-10">
                                                <label>{{$view->mailingAddress->states->name ?? 'No state name'}}</label>
                                            </div>
                                        </div>

                                        <div class="form-group row" style="margin: -12px">
                                            <label class="col-sm-2 col-form-label">City Name</label>
                                            <div class="col-sm-10">
                                                <label>{{$view->mailingAddress->city_name ?? 'No city name'}}</label>
                                            </div>
                                        </div>

{{--                                        <div class="form-group row" style="margin: -12px">--}}
{{--                                            <label class="col-sm-2 col-form-label">Zip Code</label>--}}
{{--                                            <div class="col-sm-10">--}}
{{--                                                <label>{{$data->mailingAddress->zip_code ?? 'No zip code'}}</label>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12" style="text-align: center">
                        <a class="btn waves-effect waves-light btn-grd-primary" href="{{url('Kobe/user-management/list?page='.request('page'))}}">
                            <i class="fa fa-backward"></i>Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
