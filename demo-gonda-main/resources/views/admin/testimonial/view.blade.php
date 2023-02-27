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
                                    <form id="profile-update-form" method="post" action="{{route('admin.testimonial.view',['id'=>base64_encode($view->id), 'page'=>request('page')])}}" novalidate>
                                        @csrf
                                        <div class="form-group row" style="margin: -12px">
                                            <label class="col-sm-2 col-form-label">First Name</label>
                                            <div class="col-sm-10">
                                                <label>{{$view->first_name ?? ''}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="margin: -12px">
                                            <label class="col-sm-2 col-form-label">Last Name</label>
                                            <div class="col-sm-10">
                                                <label>{{$view->last_name ?? ''}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="margin: -12px">
                                            <label class="col-sm-2 col-form-label">Designation</label>
                                            <div class="col-sm-10">
                                                <label>{{$view->designation ?? ''}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="margin: -12px">
                                            <label class="col-sm-2 col-form-label">Description</label>
                                            <div class="col-sm-10">
                                                <label>{!! $view->description ?? '' !!}</label>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="margin: -12px">
                                            <label class="col-sm-2 col-form-label">Image</label>
                                            <div class="col-sm-10">
                                                <label><img src="{{asset('public/storage/uploads/testimonial/'.$view->image ?? '')}}" style="width: 20px; height: 20px"></label>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="margin: -12px">
                                            <label class="col-sm-2 col-form-label">Status</label>
                                            <div class="col-sm-10">
                                                <label>{{$view->status ? 'Active' : 'Inactive'}}</label>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12" style="text-align: center">
                        <a class="btn waves-effect waves-light btn-grd-primary" href="{{url('Kobe/testimonial/list?page='.request('page'))}}">
                            <i class="fa fa-backward"></i>Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
