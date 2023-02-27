@extends('admin.include.layouts')
@section('content')
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
{{--                <div class="form-group row">--}}
{{--                    <div class="col-md-12">--}}
{{--                        <a class="btn waves-effect waves-light btn-grd-primary" href="{{route('admin.footer.add')}}">--}}
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
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($list as $key => $data)
                                        <tr>
                                            <td>{{$list->firstItem() + $key}}</td>
                                            <td>{{$data->title ?? ''}}</td>

                                            <td>
                                                <a onclick="window.open('{{asset("public/storage/uploads/footer/".$data->image )}}')" id="all-scroll">
                                                    <img src="{{asset('public/storage/uploads/footer/'.$data->image ?? '')}}" style="width: 40px; height: 40px">
                                                </a>
                                            </td>
                                            <td>
                                                <label class="label {{$data->status == 1 ? 'label-success' : 'label-danger'}}">{{$data->status == 1 ? 'Active' : 'Inactive'}}</label>
                                            </td>
                                            <td>
                                                <a class="btn waves-effect waves-light btn-grd-info" href="{{route('admin.footer.view',['id'=>base64_encode($data->id), 'page'=>request('page')])}}" style="padding: 4px;">
                                                    <i class="fa fa-eye"></i>  View
                                                </a>
                                                <a class="btn waves-effect waves-light btn-grd-success" href="{{route('admin.footer.edit', ['id'=>base64_encode($data->id), 'page'=>request('page')])}}" style="padding: 4px;">
                                                    <i class="fa fa-pencil-square-o"></i>Edit
                                                </a>
                                                <a class="btn waves-effect waves-light btn-grd-danger" onclick="return confirm('Are you sure?')"  href="{{route('admin.footer.delete', ['id'=>base64_encode($data->id)])}}" style="padding: 4px;">
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
                    { orderable: false, targets: 2 },
                    { orderable: false, targets: 4 }
                ],
            });
        });

    </script>
@endsection
