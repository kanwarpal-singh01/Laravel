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
                                    <form id="profile-update-form" method="get" action="{{route('admin.document.view',['id'=>base64_encode($view->id), 'page'=>request('page')])}}" novalidate>
                                        @csrf
                                        <div class="form-group row" style="margin: -12px">
                                            <label class="col-sm-2 col-form-label">Sender Name</label>
                                            <div class="col-sm-10">
                                                <label>{{ucfirst($view->user->first_name." ".$view->user->last_name ?? 'No sender name')}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="margin: -12px">
                                            <label class="col-sm-2 col-form-label">Receiver Name</label>
                                            <div class="col-sm-10">
                                                <label>{{ucfirst($view->contact->userByMobileNumber->first_name ?? 'No contact name')}}</label>
                                            </div>
                                        </div>
{{--                                        <div class="form-group row" style="margin: -12px">--}}
{{--                                            <label class="col-sm-2 col-form-label">Content</label>--}}
{{--                                            <div class="col-sm-10">--}}
{{--                                                <label>{{$view->content ?? 'No content'}}</label>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group row" style="margin: -12px">--}}
{{--                                            <label class="col-sm-2 col-form-label">State Name</label>--}}
{{--                                            <div class="col-sm-10">--}}
{{--                                                <label>{{$view->stateUs->name ?? 'No state name'}}</label>--}}

{{--                                            </div>--}}
{{--                                        </div>--}}

                                        <div class="form-group row" style="margin: -12px">
                                            <label class="col-sm-2 col-form-label">Template Name</label>
                                            <div class="col-sm-10">
                                                <label>{{$view->templateData->title ?? 'No template name'}}</label>
                                                <button type="button" class="btn waves-effect waves-light btn-grd-info" data-toggle="modal" data-target="#exampleModal" style="padding: 2px;margin-left: 771px;">
                                                    <i class="fa fa-eye"></i>  View Document
                                                </button>
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
                        <a class="btn waves-effect waves-light btn-grd-primary" href="{{url('Kobe/document-management/list?page='.request('page'))}}">
                            <i class="fa fa-backward"></i>Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="security-pin" method="post" action="{{route('admin.document.security.pin')}}" novalidate>
                        @csrf
                        <input type="hidden" name="document_id" value="{{$view->id}}">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Enter Security Pin</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control @error('secret_pin') is-invalid @enderror" id="secret_pin" name="secret_pin" placeholder="Security Pin">
                                @error('secret_pin')
                                <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Confirm Security Pin</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control @error('secret_pin_confirmation') is-invalid @enderror" id="secret_pin_confirmation" name="secret_pin_confirmation" placeholder="Confirm Security Pin">
                                @error('secret_pin_confirmation')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2"></label>
                            <div class="col-sm-10">
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src={{ asset('public/challenge-validation/validation.js') }}></script>
@endsection
