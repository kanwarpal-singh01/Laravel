@extends('admin.include.layouts')
@section('content')
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="form-group row">
                    <div class="col-md-12">
                        <a class="btn waves-effect waves-light btn-grd-primary" href="{{ route('admin.keyword.add') }}">
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
                                            <th>Keyword</th>
                                            <th>Question</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="row_position">
                                        @forelse($list as $key => $data)
                                            <tr id="{{ $data->id }}">
                                                <td>{{ $list->firstItem() + $key }}</td>
                                                <td>{{ $data->keyword ?? 'No Keyword' }}</td>
                                                <td>{{ Illuminate\Support\Str::limit($data->question, 10, $end = '.......') }}
                                                </td>
                                                <td>
                                                    <label
                                                        class="label {{ $data->status == 1 ? 'label-success' : 'label-danger' }}">{{ $data->status == 1 ? 'Active' : 'Inactive' }}</label>
                                                </td>

                                                <td>
                                                    <a class="btn waves-effect waves-light btn-grd-info"
                                                        href="{{ route('admin.keyword.view', ['id' => base64_encode($data->id), 'page' => request('page')]) }}"
                                                        style="padding: 4px;">
                                                        <i class="fa fa-eye"></i> View
                                                    </a>
                                                    <a class="btn waves-effect waves-light btn-grd-success"
                                                        href="{{ route('admin.keyword.edit', ['id' => base64_encode($data->id), 'page' => request('page')]) }}"
                                                        style="padding: 4px;">
                                                        <i class="fa fa-pencil-square-o"></i> Edit
                                                    </a>
                                                    <a class="btn waves-effect waves-light btn-grd-danger"
                                                        onclick="return confirm('Are you sure?')"
                                                        href="{{ route('admin.keyword.delete', ['id' => base64_encode($data->id)]) }}"
                                                        style="padding: 4px;">
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
        $(document).ready(function() {
            $('#data-table').DataTable({
                "paging": false,
                columnDefs: [{
                    orderable: false,
                    targets: 4
                }]
            });
        });
    </script>
    <script type="text/javascript">
        $(".row_position").sortable({

            delay: 150,

            stop: function() {

                var selectedData = new Array();

                $('.row_position>tr').each(function() {

                    selectedData.push($(this).attr("id"));

                });

                updateOrder(selectedData);

            }

        });

        function updateOrder(data) {
            $.ajax({
                url: "{{ route('admin.keyword.list') }}",
                type: 'post',

                data: {
                    "_token": "{{ csrf_token() }}",
                    "position": data
                },
                success: function(data) {
                    toastr.success('Your Change Successfully Saved.');
                },
                error: function() {
                    toastr.error('Something went wrong');
                }
            })
        }
    </script>

@endsection
