@extends('admin.include.layouts')
@section('content')
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
{{--                <div class="form-group row">--}}
{{--                    <div class="col-md-12">--}}
{{--                        <a class="btn waves-effect waves-light btn-grd-primary" href="{{route('admin.home.section6.add')}}">--}}
{{--                            <i class="fa fa-plus"></i>Add--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="page-body">
                    <div class="card">
                        <div class="card-block">
                            <div class="table-responsive dt-responsive">
                                <table id="data-table" class="table table-striped table-bordered nowrap">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Title</th>
                                        <th>Price</th>
                                        <th>Credit Points</th>
                                        <th>Type Ios</th>
                                        <th>Type Android</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($list as $key => $data)
                                        <tr>
                                            <td>{{$list->firstItem() + $key}}</td>
                                            <td>{{$data->title ?? ''}}</td>
                                            <td>{{$data->price ?? ''}}</td>
                                            <td>{{$data->credit_points ?? ''}}</td>
                                            <td>{{$data->type_ios ?? ''}}</td>
                                            <td>{{$data->type_android ?? ''}}</td>

                                            <td>
                                                <label class="label {{$data->status == 1 ? 'label-success' : 'label-danger'}}">{{$data->status == 1 ? 'Active' : 'Inactive'}}</label>
                                            </td>
                                            <td>
                                                <a class="btn waves-effect waves-light btn-grd-info" href="{{route('admin.home.section6.view',['id'=>base64_encode($data->id), 'page'=>request('page')])}}" style="padding: 4px;">
                                                    <i class="fa fa-eye"></i>  View
                                                </a>

                                                <a class="btn waves-effect waves-light btn-grd-success" href="{{route('admin.home.section6.edit', ['id'=>base64_encode($data->id), 'page'=>request('page')])}}" style="padding: 4px;">
                                                    <i class="fa fa-pencil-square-o"></i>Edit
                                                </a>
                                                <a class="btn waves-effect waves-light btn-grd-danger" onclick="return confirm('Are you sure?')"  href="{{route('admin.home.section6.delete', ['id'=>base64_encode($data->id)])}}" style="padding: 4px;">
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
                    { orderable: false, targets: 7 }
                ]
            });
        });

    </script>
@endsection
