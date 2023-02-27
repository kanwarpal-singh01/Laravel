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
                                    <form id="profile-update-form" method="post" action="{{route('admin.sub.admin.management.view',['id'=>base64_encode($view->id), 'page'=>request('page')])}}" novalidate>
                                        @csrf
                                        <div class="form-group row" style="margin: -12px;">
                                            <label class="col-sm-2 col-form-label">Name</label>
                                            <div class="col-sm-10">
                                                <label>{{$view->name ?? ''}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="margin: -12px;">
                                            <label class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <label>{{$view->email ?? ''}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="margin: -12px;">
                                            <label class="col-sm-2 col-form-label">Mobile Number</label>
                                            <div class="col-sm-10">
                                                <label>{{$view->mobile_number ?? ''}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="margin: -12px;">
                                            <label class="col-sm-2 col-form-label">Updated By</label>
                                            <div class="col-sm-10">
                                                <label>{{ucfirst($view->SubAdminManagement->name ?? '')}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="margin: -12px;">
                                            <label class="col-sm-2 col-form-label">Status</label>
                                            <div class="col-sm-10">
                                                <label class="col-form-label">{{$view->status ? 'Active' : 'Inactive'}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="margin: -12px;">
                                            <label class="col-sm-2 col-form-label"><strong>Permission</strong></label>
                                            <div class="table-responsive">
                                                <table class="table table-xs">
                                                    <thead>
                                                    <tr>
                                                        <th>Module Name</th>
                                                        <th>Add</th>
                                                        <th>Edit</th>
                                                        <th>Delete</th>
                                                        <th>View</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @forelse($view->permissionModule as $data)
                                                        <tr>
                                                            <td>
                                                                <label class="col-form-label">{{$data->permission->module_name ?? ''}}</label>
                                                            </td>
                                                            <td>
                                                                <label class="col-form-label">{{$data->add === 1 ? 'Yes' : 'No'}}</label>
                                                            </td>
                                                            <td>
                                                                <label class="col-form-label">{{$data->edit === 1 ? 'Yes' : 'No'}}</label>
                                                            </td>
                                                            <td>
                                                                <label class="col-form-label">{{$data->delete === 1 ? 'Yes' : 'No'}}</label>
                                                            </td>
                                                            <td>
                                                                <label class="col-form-label">{{$data->view === 1 ? 'Yes' : 'No'}}</label>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                    @endforelse
                                                    </tbody>
                                                </table>
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
                        <a class="btn waves-effect waves-light btn-grd-primary" href="{{url('admin/sub-admin-management/list?page='.request('page'))}}">
                            <i class="fa fa-backward"></i>Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
