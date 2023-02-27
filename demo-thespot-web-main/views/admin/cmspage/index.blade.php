    <?php $url = Request::segments();?>
    @extends('layouts.admin',['pageHeader'=>'Users Management'])
    @section('content')

    <div class="card tableCard code-table">
        <div class="card-header">
            <h5>Content Management</h5>
        </div>
        @include('includes.flash')
        <div class="card-block pb-0">
            <div class="table-responsive">
                <table class="table table-hover table-list">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Page Title</th>
                            <th>Meta Title</th>
                            <th>Meta Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        if(count($records)>0)
                        {

                            $page = $records->currentPage();
                            $pagelimit = config("Settings.AdminPageLimit");
                            $i = ($page * $pagelimit) - ($pagelimit); ?>
                            @foreach ($records as $row)
                            <?php $i++ ?>
                            <tr class="gradeY">
                                <td>{{$i}}.</td>
                                <td>{{$row->title_en}}</td>
                                <td>{{$row->title_ar}}</td>
                                <td style="word-wrap: break-word">{{$row->meta_description}}</td>
                                <!-- <td class="center">
                                    @if($row->status == 'Deactive')
                                    <a class="btn btn-danger btn-sm tip-bottom btn-mini"  role="button" href="{{ route('changeAdStatus',['id'=>base64_encode($row->id),'status'=>'Active']) }}"  title="Click to Active" onclick="return confirm('Do you want to active ?')" >Inactive</a>
                                    @endif
                                    @if($row->status == 'Active')
                                    <a class="tip-bottom btn-mini label theme-bg f-12 text-white"  role="button"  href="{{ route('changeAdStatus',['id'=>base64_encode($row->id),'status'=>'Deactive']) }}" class="tip-left" title="Click to inactive" onclick="return confirm('Do you want to inactive ?')">Active</a>
                                    @endif
                                </td> -->
                                <td class="center">
                                    <a  href="{{ route('updatepage',['id'=>base64_encode($row->id)])}}"  class="mb-2 mr-2 "><i class="fas fa-edit"></i></a>
                                    <a  href="{{ route($row->page_url)}}" target="_blank"  class="mb-2 mr-2 "><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        <?php 
                    } else { ?>
                            <tr><td colspan='10' style="text-align: center">No Result Found</td></tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php if(!empty($records)) { ?>
                    <div class="pagination-parent">
                        <div class="left">
                            Total Records:<strong>{{$records->total()}}</strong> Page of pages: <strong>{{$records->currentPage()}}</strong> of <strong>{{$records->lastPage()}}</strong>
                        </div>
                        <div class="pagination">
                            {{  $records->appends(Request::only('q'))->links() }}
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
@stop
@section('customJs');
<link href="{{ asset('css/datepicker.min.css') }}" rel="stylesheet">
<script src="{{ asset('js/datepicker.min.js') }}"></script>
<script>
$(".start_date").datepicker( {
    //settings
    startDate: new Date('2020-12-01'),
    endDate: new Date()
}).on('changeDate', function(ev) {
    //Get actual date objects from both
    var dt1 = $('.start_date').data('datepicker').viewDate;
    var dt2 = $('.end_date').data('datepicker').viewDate;
    if (dt2 < dt1) {
        //If #dateEnd is before .start_date, set #dateEnd to #dateStart
        $('.end_date').datepicker('update', $('.start_date').val());
    }
    //Always limit #dateEnd's starting date to #dateStart's value
    $('.end_date').datepicker('setStartDate', $('.end_date').val());
});

$(".end_date").datepicker( {
    startDate: $('.start_date').val(),
    endDate: new Date()
});
</script>
@stop
