@extends('admin.include.layouts')
@section('content')
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="form-group row">
                    <div class="col-md-12">
                        <a class="btn waves-effect waves-light btn-grd-primary" href="{{route('admin.user.add')}}">
                            <i class="fa fa-plus"></i>Add
                        </a>
                    </div>
                </div>
                <div class="page-body">
                    <div class="card">
                        <div class="card-block">
                            <div class="table-responsive dt-responsive">
                                <table id="data-table" class="table table-striped table-bordered nowrap">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Mobile Number</th>
{{--                                        <th>Document Count</th>--}}
{{--                                        <th>Updated By</th>--}}
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($list as $key => $data)
                                        <tr>
                                            <td>{{$list->firstItem() + $key}}</td>

                                            <td>{{ucfirst($data->first_name ?? 'No first name')}}</td>
                                            <td>{{ucfirst($data->last_name ?? 'No last name')}}</td>
                                            <td>{{$data->email ?? 'No email'}}</td>
                                            <td>{{$data->mobile_number ?? ''}}</td>
{{--                                            <td>{{count($data->document) ?? 0}}</td>--}}
{{--                                            <td>{{ucfirst($data->SubAdminManagement->full_name ?? 'No name')}}</td>--}}
                                            <td>
                                                <label class="label {{$data->status == 1 ? 'label-success' : 'label-danger'}}">{{$data->status == 1 ? 'Active' : 'InActive'}}</label>
                                            </td>
                                            <td>
                                                <a class="btn waves-effect waves-light btn-grd-info" href="{{route('admin.user.view',['id'=>base64_encode($data->id), 'page'=>request('page')])}}" style="padding: 4px;">
                                                    <i class="fa fa-eye"></i>  View
                                                </a>
                                                <a class="btn waves-effect waves-light btn-grd-success" href="{{route('admin.user.edit', ['id'=>base64_encode($data->id), 'page'=>request('page')])}}" style="padding: 4px;">
                                                    <i class="fa fa-pencil-square-o"></i>Edit
                                                </a>

                                                    <a class="btn waves-effect waves-light btn-grd-danger" onclick="return confirm('Are you sure you want to delete this data?')"  href="{{route('admin.user.delete', ['id'=>base64_encode($data->id)])}}" style="padding: 4px;">
                                                        <i class="fa fa-trash"></i>Delete
                                                    </a>

                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                    </tbody>
                                    {{ $list->links() }}
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready( function () {
            $('#data-table').DataTable({
                'paging': false,
                columnDefs: [
                    { orderable: false, targets: 6 }
                ]
            });
        });

    </script>
@endsection
