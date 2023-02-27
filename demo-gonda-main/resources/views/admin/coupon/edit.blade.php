@extends('admin.include.layouts')
@section('content')
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="form-group row">
                    <div class="col-md-12" style="margin-right: 873px;">
                        <a class="btn waves-effect waves-light btn-grd-primary" href="{{url('Kobe/coupon/list?page='.request('page'))}}">
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
                                    <form id="" method="post" action="{{route('admin.coupon.edit', ['id'=>base64_encode($edit->id), 'page'=>request('page')])}}" novalidate enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Promo Code</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control @error('promo_code') is-invalid @enderror" id="promo_code" name="promo_code" placeholder="Promo Code" value="{{$edit->promo_code}}">
                                                @error('promo_code')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Discount</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control @error('discount') is-invalid @enderror" id="discount" name="discount" placeholder="Discount" value="{{$edit->discount}}">
                                                @error('discount')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Start Date</label>
                                            <div class="col-sm-10">
                                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" placeholder="Start Date" value="{{$edit->start_date}}">
                                                @error('start_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">End Date</label>
                                            <div class="col-sm-10">
                                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" placeholder="End Date" value="{{$edit->end_date}}">
                                                @error('end_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Months</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control @error('months') is-invalid @enderror" id="months" name="months" placeholder="Months" value="{{$edit->months}}">
                                                @error('months')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Status</label>
                                            <div class="form-radio">
                                                <div class="col-sm-5 radio radio-outline radio-inline">
                                                    <label>
                                                        <input type="radio" name="status" id="active" value="1" {{$edit->status === 1 ? 'checked' : ''}}>
                                                        <i class="helper"></i>Active
                                                    </label>
                                                </div>
                                                <div class="col-sm-6 radio radio-outline radio-inline">
                                                    <label>
                                                        <input type="radio" name="status" id="inactive" value="0" {{$edit->status === 0 ? 'checked' : ''}}>
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
@endsection
