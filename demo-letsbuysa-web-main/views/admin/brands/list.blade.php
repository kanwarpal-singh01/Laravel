@include('includes-file.header')
@include('includes-file.sidebar')
<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="container-fluid">
            <div class="row pt-2 pb-2">
                <div class="col-sm-9">
                    <h4 class="page-title">Brands Tables</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Brands List</li>
                    </ol>
                </div>
                <div class="col-sm-3">
                    <a href="{{route('brands_add')}}">
                        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Add New</button>
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-table"></i> Brands List</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="list" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name(English)</th>
                                        <th>Name(Arabic)</th>
                                        <th>Image (English)</th>
                                        <th>Image (Arabic)</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @forelse($list as $key=>$record)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>{{$record->name_en ?? ''}}</td>
                                            <td>{{$record->name_ar ?? ''}}</td>
                                            <td><img src="{{asset('public/images/brands/'.$record->image_en ?? '')}}"  alt="User Image" width="50"></td>
                                            <td><img src="{{asset('public/images/brands/'.$record->image_ar ?? '')}}"  alt="User Image" width="50"></td>
                                            <td>{{$record->status === 1 ? 'Enabled' : 'Disabled'}}</td>
                                            <td>
                                                @if(auth()->user()->role === 1)
                                                <a href="{{route('brands_edit', base64_encode($record->id))}}">
                                                  <button type="button" class="btn btn-success waves-effect waves-light m-1"> <i class="fa fa-pencil"></i> </button></a>
                                                <a href="{{route('brands_delete', base64_encode($record->id))}}">
                                                    <button onclick="return confirm('are you want to delete this record');" type="button" class="btn btn-danger waves-effect waves-light m-1"> <i class="fa fa-trash-o"></i> </button></a>
                                                @endif
                                                    @php $permission = permission(); @endphp

                                                    @if(auth()->user()->role === 3 && isset($permission['brands']) && $permission['brands']['edit'] === 1)
                                                        <a href="{{route('brands_edit', base64_encode($record->id))}}">
                                                            <button type="button" class="btn btn-success waves-effect waves-light m-1"> <i class="fa fa-pencil"></i> </button></a>
                                                    @endif
                                                    @if(auth()->user()->role === 3 && isset($permission['brands']) && $permission['brands']['delete'] === 1)
                                                        <a href="{{route('brands_delete', base64_encode($record->id))}}">
                                                            <button onclick="return confirm('are you want to delete this record');" type="button" class="btn btn-danger waves-effect waves-light m-1"> <i class="fa fa-trash-o"></i> </button></a>
                                                    @endif
                                            </td>
                                        </tr>

                                    @empty

                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('includes-file.footer')
    @if(session()->has('info'))
        <script>
            round_info_noti_delete();
        </script>
    @endif
    <script>
        $(document).ready(function() {
            //Default data table
            $('#default-datatable').DataTable();


            var table = $('#example').DataTable( {
                lengthChange: false,
                buttons: [ 'copy', 'excel', 'pdf', 'print', 'colvis' ]
            } );

            table.buttons().container()
                .appendTo( '#example_wrapper .col-md-6:eq(0)' );

        } );
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#list').DataTable({
                columnDefs: [
                    { orderable: false, targets: 3 },
                    { orderable: false, targets: 4 },
                    { orderable: false, targets: 6 }
                ],
            });
        });
    </script>
    <script>
        $(document).ready(function(){
            $(".alert").slideDown(300).delay(5000).slideUp(300);
        });
        function CheckDate(){
            var startdate=document.getElementById('start_date').value;
            var enddate=document.getElementById('end_date').value;
            var sd = Date.parse(startdate);
            var ed = Date.parse(enddate);
            if(ed<sd){
                alert('Reverse Date Formate Not Allowed');
                return false;
            }
            else{
                document.getElementById("filterdata").submit();
            }
        }
        $('#start_date').datepicker({
            autoclose: true,
            todayHighlight: true
        });
        $('#end_date').datepicker({
            autoclose: true,
            todayHighlight: true
        });
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
        });
    </script>
    </body>
    </html>



