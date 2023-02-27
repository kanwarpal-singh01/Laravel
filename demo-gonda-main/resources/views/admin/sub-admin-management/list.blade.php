@extends('admin.include.layouts')
@section('content')
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="form-group row">
                    <div class="col-md-12">
                        <a class="btn waves-effect waves-light btn-grd-primary" href="{{route('admin.sub.admin.management.add')}}">
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
                                        <th>Updated By</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($list as $key => $data)
                                        <tr>
                                            <td>{{$list->firstItem() + $key}}</td>

                                            <td>{{ucfirst($data->first_name ?? '')}}</td>
                                            <td>{{ucfirst($data->last_name ?? '')}}</td>
                                            <td>{{$data->email ?? ''}}</td>
                                            <td>{{$data->mobile_number ?? ''}}</td>
                                            <td>{{ucfirst($data->SubAdminManagement->first_name ?? '').' '.ucfirst($data->SubAdminManagement->last_name ?? '')}}</td>
                                            <td>
                                                <label class="label {{$data->status == 1 ? 'label-success' : 'label-danger'}}">{{$data->status == 1 ? 'Store' : 'Deleted'}}</label>
                                            </td>
                                            <td>
                                                <a class="btn waves-effect waves-light btn-grd-info" href="{{route('admin.sub.admin.management.view', ['id' => base64_encode($data->id), 'page'=>request('page')])}}" style="padding: 4px;">
                                                    <i class="fa fa-eye"></i>
                                                    View
                                                </a>
                                                <a class="btn waves-effect waves-light btn-grd-success" href="{{route('admin.sub.admin.management.edit', ['id'=>base64_encode($data->id), 'page'=>request('page')])}}" style="padding: 4px;">
                                                    <i class="fa fa-pencil-square-o"></i>Edit
                                                </a>
                                                @if($data->status == 1)
                                                <a class="btn waves-effect waves-light btn-grd-danger" onclick="return confirm('Are you sure you want to delete this data?')"  href="{{route('admin.sub.admin.management.delete', ['id'=>base64_encode($data->id)])}}" style="padding: 4px;">
                                                    <i class="fa fa-trash"></i>Delete
                                                </a>
                                                @else
                                                <a class="btn waves-effect waves-light btn-grd-info" onclick="return confirm('Are you sure you want to activate this data?')"  href="{{route('admin.sub.admin.management.delete', ['id'=>base64_encode($data->id)])}}" style="padding: 4px;">
                                                    <i class="fas fa-store-alt"></i>Store
                                                </a>
                                                @endif
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
                "paging": false,
                columnDefs: [
                    { orderable: false, targets: 7 }
                ],
            });
        });

    </script>
@endsection
