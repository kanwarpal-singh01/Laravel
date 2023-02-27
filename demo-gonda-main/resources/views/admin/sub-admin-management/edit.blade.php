@extends('admin.include.layouts')
@section('content')
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="form-group row">
                    <div class="col-md-12" style="margin-right: 873px;">
                        <a class="btn waves-effect waves-light btn-grd-primary" href="{{url('admin/sub-admin-management/list?page='.request('page'))}}">
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
                                    <form id="sub-admin-management-form" method="post" action="{{route('admin.sub.admin.management.edit',['id'=>base64_encode($edit->id), 'page'=>request('page')])}}" novalidate>
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">First Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" placeholder="First Name" value="{{$edit->first_name ?? ''}}">
                                                @error('first_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Last Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" placeholder="Last Name" value="{{$edit->last_name ?? ''}}">
                                                @error('last_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email" value="{{$edit->email ?? '' }}">
                                                @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Mobile Number</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control @error('mobile_number') is-invalid @enderror" id="mobile_number" name="mobile_number" placeholder="Mobile Number" value="{{$edit->mobile_number ?? '' }}">
                                                @error('mobile_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
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
                                                    @forelse($permission_data as $data)
                                                        <tr>
                                                            <td>
                                                                <div class="border-checkbox-section">
                                                                    <div class="border-checkbox-group border-checkbox-group-info">
                                                                        <input class="border-checkbox" type="checkbox" id="{{$data->slug}}" name="module_name[]" value="{{$data->id}}" {{in_array($data->id, $permissions["permission"]) ? 'checked' : ''}} onclick="permissionCheckboxToggle('{{$data->slug}}')">
                                                                        <label class="border-checkbox-label" for="{{$data->slug}}">{{$data->module_name}}</label>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="border-checkbox-section">
                                                                    <div class="border-checkbox-group border-checkbox-group-primary not-allow">
                                                                        <input class="border-checkbox " type="checkbox" id="add_{{$data->slug}}" name="add[]" value="{{$data->id}}" {{!empty($permissions[$data->id]['add']) ? 'checked' : ''}} {{in_array($data->id, $permissions["permission"]) ? '' : 'disabled'}}>
                                                                        <label class="border-checkbox-label" for="add_{{$data->slug}}"></label>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="border-checkbox-section ">
                                                                    <div class="border-checkbox-group border-checkbox-group-success">
                                                                        <input class="border-checkbox not-allow" type="checkbox" id="edit_{{$data->slug}}" name="edit[]" value="{{$data->id}}" {{!empty($permissions[$data->id]['edit']) ? 'checked' : ''}} {{in_array($data->id, $permissions["permission"]) ? '' : 'disabled'}}>
                                                                        <label class="border-checkbox-label" for="edit_{{$data->slug}}"></label>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="border-checkbox-section">
                                                                    <div class="border-checkbox-group border-checkbox-group-danger">
                                                                        <input class="border-checkbox not-allow" type="checkbox" id="delete_{{$data->slug}}" name="delete[]" value="{{$data->id}}" {{!empty($permissions[$data->id]['delete']) ? 'checked' : ''}} {{in_array($data->id, $permissions["permission"]) ? '' : 'disabled'}}>
                                                                        <label class="border-checkbox-label" for="delete_{{$data->slug}}"></label>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="border-checkbox-section">
                                                                    <div class="border-checkbox-group border-checkbox-group-info">
                                                                        <input class="border-checkbox not-allow" type="checkbox" id="view_{{$data->slug}}" name="view[]" value="{{$data->id}}" {{!empty($permissions[$data->id]['view']) ? 'checked' : ''}} {{in_array($data->id, $permissions["permission"]) ? '' : 'disabled'}}>
                                                                        <label class="border-checkbox-label" for="view_{{$data->slug}}"></label>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                    @endforelse
                                                    </tbody>
                                                </table>
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
    <script type="text/javascript">
        function permissionCheckboxToggle(module_id) {

            if ($('#'+module_id).is(":checked")) {

                $('#add_'+module_id).prop('disabled',false);
                $('#edit_'+module_id).prop('disabled',false);
                $('#delete_'+module_id).prop('disabled',false);
                $('#view_'+module_id).prop('disabled',false);
            }
            else {
                $('#add_'+module_id).prop({'disabled':true,'checked':false});
                $('#edit_'+module_id).prop({'disabled':true,'checked':false});
                $('#delete_'+module_id).prop({'disabled':true,'checked':false});
                $('#view_'+module_id).prop({'disabled':true,'checked':false});
            }

        }
    </script>
@endsection
