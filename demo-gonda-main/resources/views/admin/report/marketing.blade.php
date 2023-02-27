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
                                        <th>Email</th>
                                        <th>What describes you best?</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($list as $key => $data)
                                        <tr>
                                            <td>{{$list->firstItem() + $key}}</td>
                                            <td>{{$data->email ?? 'No email'}}</td>
                                            <td>{{config('gonda.marketing.'.$data->describe_id)}}</td>
                                            <td>{{date_format($data->created_at ?? '', 'Y/m/d')}}</td>
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
                "paging": false
            });
        });

    </script>
@endsection
@section('scripts')


    {{--    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">--}}
    {{--    <link rel="stylesheet" href="/resources/demos/style.css">--}}
    {{--    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>--}}
    {{--    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>--}}
    {{--    <script type="text/javascript">--}}
    {{--        $(document).ready( function () {--}}
    {{--            $('#from').datepicker({--}}
    {{--                uiLibrary: 'bootstrap4'--}}
    {{--            });--}}

    {{--            $('#to').datepicker({--}}
    {{--                uiLibrary: 'bootstrap4'--}}
    {{--            });--}}
    {{--        });--}}
    {{--    </script>--}}
@endsection
