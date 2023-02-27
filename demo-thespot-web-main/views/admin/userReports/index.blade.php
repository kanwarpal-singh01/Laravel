    <?php $url = Request::segments(); ?>
    @extends('layouts.admin',['pageHeader'=>'Users Management'])
    @section('content')

    <div class="card tableCard code-table">
        <div class="card-header">
            <h5>USERS REPORTS MANAGEMENT</h5>
        </div>
        <div class="card-header">
            {{ Form::open(array('route'=>['reportUserList'], 'class'=>"form-inline md-form mr-auto", 'method' => 'get','autocomplete'=>'off')) }}
            <div class="input-group">
                <input class="form-control" value="<?php if (!empty($query_name)) {
                                                        echo $query_name;
                                                    } ?>" placeholder='Search' name="name" type="text">
                <div class="input-group-append">
                    <button class="input-group-text submitSearchForm float-left" type="submit"><i class="feather icon-search"></i></button>
                </div>
                <div class="input-group-append">
                    <a href="{{ route('reportUserList') }}" class="input-group-text btn-danger text-danger float-left"><i class="fa fa-times"></i></a>
                </div>
            </div>
            {{ Form::close() }}
        </div>
        @include('includes.flash')
        <div class="card-block pb-0">
            <div class="table-responsive">
                <table class="table table-hover table-list">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Reported By</th>
                            <th>User Reported</th>
                            <th>Reason</th>
                            <th>Suspended Status</th>
                            <th>Reported Date</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        if (count($reports) > 0) {
                            $page = $reports->currentPage();
                            $i = ($page * $pagelimit) - ($pagelimit); ?>
                            @foreach ($reports as $row)
                            <?php $i++; ?>
                            <tr class="gradeY">
                                <td>{{$i}}.</td>
                                <td>{{$row->reporter_name}}</td>
                                <td>{{$row->reported_name}}</td>
                                <td>{{$row->reason}}</td>
                                <td class="center">
                                    @if($row->reported_status != 5)
                                    <a class="btn btn-success btn-sm tip-bottom btn-mini" role="button" href="{{ route('userChangeStatus',['suspended',base64_encode($row->reported_id),5]) }}" title="Click to Active" onclick="return confirm('Do you want to Suspended this account ?')">Active</a>
                                    @endif
                                    @if($row->reported_status == 5)
                                    <a class="tip-bottom btn btn-danger btn-sm f-12 text-white" role="button" href="{{ route('userChangeStatus',['activate',base64_encode($row->reported_id),1]) }}" class="tip-left" title="Click to inactive" onclick="return confirm('Do you want to suspended ?')">Suspended</a>
                                    @endif
                                </td>
                                <td>{{$row->created_at->format('d-m-Y h:i A')}}</td>
                            </tr>
                            @endforeach
                        <?php } else { ?>
                            <tr>
                                <td colspan='10' style="text-align: center">No Result Found</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                {{ $reports->links()}}
            </div>
        </div>
    </div>
    @stop
    @section('customJs');
    <link href="{{ asset('css/datepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/datepicker.min.js') }}"></script>
    <script>
        $(".start_date").datepicker({
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

        $(".end_date").datepicker({
            startDate: $('.start_date').val(),
            endDate: new Date()
        });
    </script>
    @stop