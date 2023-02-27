@extends('admin.include.layouts')
@section('content')
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
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
                                        <th>Message</th>
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
                                            <td>{{ucfirst($data->message ?? '')}}</td>
                                            <td>
                                                <a class="btn waves-effect waves-light btn-grd-info" href="{{route('admin.contact.reply', ['id'=>base64_encode($data->id), 'page'=>request('page')])}}" style="padding: 4px;">
                                                    <i class="fa fa-reply"></i>Reply
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
                    { orderable: false, targets: 6 },
                ],
            });
        });

    </script>
@endsection
