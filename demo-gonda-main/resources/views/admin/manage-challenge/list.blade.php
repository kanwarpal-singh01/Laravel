@extends('admin.include.layouts')
@section('content')
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="form-group row">
                    <div class="col-md-12">
                        <a class="btn waves-effect waves-light btn-grd-primary" href="{{route('admin.manage.challenge.add')}}">
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
                                        <th>Title</th>
                                        <th>Category Name</th>
                                        <th>Image</th>
                                        <th>Updated By</th>
                                        <th>Date</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Description</th>
                                        <th>No Of Participants</th>
                                        <th>Challenge Type</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($list as $key => $data)
                                        <tr>
                                            <td>{{$list->firstItem() + $key}}</td>

                                            <td>{{ucfirst($data->title ?? '')}}</td>
                                            <td>{{ucfirst($data->category->name ?? '')}}</td>
                                            <td>
                                                <img src="{{asset('public/storage/uploads/manage-challenge/'.$data->image ?? '')}}" style="width: 50px; height: 50px">
                                            </td>
                                            <td>{{ucfirst($data->user->name ?? '')}}</td>
                                            <td>{{$data->date ?? ''}}</td>
                                            <td>{{$data->start_time ?? ''}}</td>
                                            <td>{{$data->end_time ?? ''}}</td>
                                            <td>{!! $data->description ?? '' !!}</td>
                                            <td>{{$data->no_of_participants ?? ''}}</td>
                                            <td>
                                                <label class="label {{$data->challenge_type == 1 ? 'label-success' : 'label-danger'}}">{{$data->challenge_type == 1 ? 'Online' : 'Callout'}}</label>
                                            </td>
                                            <td>
                                                <label class="label {{$data->status == 1 ? 'label-success' : 'label-danger'}}">{{$data->status == 1 ? 'Active' : 'InActive'}}</label>
                                            </td>
                                            <td>

                                                <a class="btn waves-effect waves-light btn-grd-success" href="{{route('admin.manage.challenge.edit', ['id'=>base64_encode($data->id), 'page'=>request('page')])}}" style="padding: 4px;">
                                                    <i class="fa fa-pencil-square-o"></i>Edit
                                                </a>

                                                <a class="btn waves-effect waves-light btn-grd-danger" onclick="return confirm('Are you sure you want to delete this data?')"  href="{{route('admin.manage.challenge.delete', ['id'=>base64_encode($data->id)])}}" style="padding: 4px;">
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
                "paging": false,
                columnDefs: [
                    { orderable: false, targets: 3 },
                    { orderable: false, targets: 12 },
                ],
            });
        });

    </script>
@endsection
