@extends('layouts.admin')
@section('content')
<style type="text/css">
   
    .carousel-control-next{
height: 10px;top: 230px;
    }
    .carousel-control-prev{
height: 10px;top: 230px;
    }
</style>
<div class="row">
    <div class="col-md-3 col-xl3">
        <div class="card daily-sales">
            <div class="card-block">
                <a href="{{route('userList')}}">
                    <h6 class="mb-4">Total Users</h6>
                    <div class="row d-flex align-items-center">
                        <div class="col-9">
                            <h3 class="f-w-300 d-flex align-items-center m-b-0"><i class="feather icon-arrow-up text-c-green f-30 m-r-10"></i>{{$totalCount}}</h3>
                        </div>

                        <div class="col-3 text-right">
                            <p class="m-b-0"></p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-xl3">
        <div class="card daily-sales">
            <div class="card-block">
                <a href="{{route('userList',['tus'=>base64_encode('fake_user')])}}">
                    <h6 class="mb-4">Total Fake Users</h6>
                    <div class="row d-flex align-items-center">
                        <div class="col-9">
                            <h3 class="f-w-300 d-flex align-items-center m-b-0"><i class="feather icon-arrow-up text-c-green f-30 m-r-10"></i>{{$dashboardCount->fake_users}}</h3>
                        </div>

                        <div class="col-3 text-right">
                            <p class="m-b-0"></p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-xl-3">
        <div class="card daily-sales">
            <div class="card-block">
                <a href="{{route('userList',['tus'=>base64_encode('active')])}}">
                    <h6 class="mb-4">Total Active Users</h6>
                    <div class="row d-flex align-items-center">
                        <div class="col-9">
                            <h3 class="f-w-300 d-flex align-items-center m-b-0"><i class="feather icon-arrow-up text-c-green f-30 m-r-10"></i>{{$dashboardCount->active_user}}</h3>
                        </div>

                        <div class="col-3 text-right">
                            <p class="m-b-0"></p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-xl-3">
        <div class="card daily-sales">
            <div class="card-block">
                <a href="{{route('userList',['tus'=>base64_encode('Not-Verified')])}}">
                    <h6 class="mb-4">Total Not Verified Users</h6>
                    <div class="row d-flex align-items-center">
                        <div class="col-9">
                            <h3 class="f-w-300 d-flex align-items-center m-b-0"><i class="feather icon-arrow-up text-c-green f-30 m-r-10"></i>{{$dashboardCount->notverified_user}}</h3>
                        </div>

                        <div class="col-3 text-right">
                            <p class="m-b-0"></p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-xl-3">
        <div class="card daily-sales">
            <div class="card-block">
                <a href="{{route('userList')}}">
                    <h6 class="mb-4">Daily profiles likes</h6>
                    <div class="row d-flex align-items-center">
                        <div class="col-9">
                            <h3 class="f-w-300 d-flex align-items-center m-b-0"><i class="feather icon-arrow-up text-c-green f-30 m-r-10"></i>{{$todayLike}}</h3>
                        </div>

                        <div class="col-3 text-right">
                            <p class="m-b-0"></p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-xl-3">
        <div class="card daily-sales">
            <div class="card-block">
                <a href="{{route('storyList')}}">
                    <h6 class="mb-4">Total Posted Story</h6>
                    <div class="row d-flex align-items-center">
                        <div class="col-9">
                            <h3 class="f-w-300 d-flex align-items-center m-b-0"><i class="feather icon-arrow-up text-c-green f-30 m-r-10"></i>{{$postedStory}}</h3>
                        </div>
                        <div class="col-3 text-right">
                            <p class="m-b-0"></p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-xl-3">
        <div class="card daily-sales">
            <div class="card-block">
                <a href="#">
                    <h6 class="mb-4">Total Hotspot </h6>
                    <div class="row d-flex align-items-center">
                        <div class="col-9">
                            <h3 class="f-w-300 d-flex align-items-center m-b-0"><i class="feather icon-arrow-up text-c-green f-30 m-r-10"></i>{{$hotspot_users}}</h3>
                        </div>
                        <div class="col-3 text-right">
                            <p class="m-b-0"></p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-xl-3">
        <div class="card daily-sales">
            <div class="card-block">
                <a href="#">
                    <h6 class="mb-4">Total Revenue</h6>
                    <div class="row d-flex align-items-center">
                        <div class="col-9">
                            <h3 class="f-w-300 d-flex align-items-center m-b-0"><i class="feather icon-arrow-up text-c-green f-30 m-r-10"></i>${{0}}</h3>
                        </div>

                        <div class="col-3 text-right">
                            <p class="m-b-0"></p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-3">
        <div class="card" >
            <div class="card-header">
                <h5>Latest 5 Uploaded Stories</h5>
            </div>
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" >
  <ol class="carousel-indicators">
    <?php
if(count($userStories)>0){
    $i=0;
    foreach ($userStories as $key => $value) { ?>
   <!--  <li data-target="#carouselExampleIndicators" data-slide-to="{{$value->id}}" class=""></li> -->
    <?php 
$i=$i+1;
   }
}?>
  </ol>
  <div class="carousel-inner">

    <?php
if(count($userStories)>0){
    $i=0;
    foreach ($userStories as $key => $value) { 
        $storyExt=explode(".", $value->story)[1];
        ?>

          <div class="carousel-item <?php if($i==0){echo "active";} ?>">
            @if($storyExt == 'mp4')
            <video width="350" height="500" controls>
  <source src="{{url('/')}}/public/uploads/stories/{{$value->story}}" type="video/mp4">
</video>

            @endif
             @if($storyExt == 'jpg' || $storyExt == 'png' || $storyExt == 'jpeg')
      <img style="max-height: 500px;height: 500px;" class="d-block w-100" src="{{url('/')}}/public/uploads/stories/{{$value->story}}" alt="{{$value->id}}">
      @endif
       <div class="carousel-caption d-none d-md-block">
    <h5 style="color: white;">{{$value->name}}</h5>
  </div>
    </div>
  <?php 
$i=$i+1;
   }
}else{ ?>
   <span style="margin: 90px;">No Stories Found !</span>
<?php }
     ?>
  
   
    
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>

        </div>
    </div>
    <div class="col-xl-9">
    <div class="card">
        <div class="card-header">
            <h5> Latest 5 Reports</h5>
            <div class="card-header">
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
                           
                            $i = 0; ?>
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
            </div>
            </div>
        </div>
       
    </div>
</div>
</div>

@stop
@section('customJs')
<link href="{{ asset('chart-morris/css/morris.css') }}" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.2/raphael-min.js"></script>
<script src="{{ asset('chart-morris/js/morris.min.js') }}"></script>
<script>
var slideIndex = 1;
showDivs(slideIndex);

function plusDivs(n) {
  showDivs(slideIndex += n);
}

function showDivs(n) {
  var i;
  var x = document.getElementsByClassName("mySlides");
  if (n > x.length) {slideIndex = 1}
  if (n < 1) {slideIndex = x.length}
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";  
  }
  x[slideIndex-1].style.display = "block";  
}
</script>
<script>
    $(document).ready(function() {
        $('.duration_change').val('Yearly').trigger('change');
        /* $.ajax({
            url:'<?= SITE_URL ?>api/dashboard_chart',
            type:'post',
            data:{'type': $('.duration_change').val(),'subscription_type': $('.package_change').val()},
            success:function(res){
                res = JSON.parse(res);
                if(res.status == 1){
                    chartData = Object.values(res.data);
                    Morris.Bar({
                        element: 'morris-bar-chart',
                        data:chartData,
                        xkey: 'label_name',
                        barSizeRatio: 0.50,
                        barGap: 2,
                        resize: true,
                        responsive:true,
                        ykeys: ['count'],
                        //ykeys: ['count', 'b', 'c'],
                        labels: ['Total User'],
                        //labels: ['Bar 1', 'Bar 2', 'Bar 3'],
                        //barColors: ["0-#1de9b6-#1dc4e9", "0-#899FD4-#A389D4", "#04a9f5"]
                        barColors: ["0-#1de9b6-#1dc4e9"]
                    });
                }else{
                    $('#morris-bar-chart').html('<h4 style="text-align:center">No Record Found</h4>');
                }
            }
        }); */
    });
    $('.duration_change,.package_change,.package_status').change(function() {
        changeValue = $(this).val();
        setChartData()
        console.log(changeValue);

    });

    function setChartData(chartData) {
        $('#morris-bar-chart').html('');
        console.log($('.duration_change').val());
        $.ajax({
            url: '<?= SITE_URL ?>api/subscription_chart',
            type: 'post',
            data: {
                'type': $('.duration_change').val(),
                'sub_type': $('.package_change').val(),
                'sub_status': $('.package_status').val()
            },
            success: function(res) {
                res = JSON.parse(res);
                if (res.status == 1) {

                    console.log(res.labels);
                    res.labels.shift();
                    console.log(res.labels);
                    chartData = Object.values(res.data);


                    /* var bar = document.getElementById("myChart").getContext('2d');
                    var theme_g1 = bar.createLinearGradient(0, 100, 0, 0);
                    theme_g1.addColorStop(0, '#1de9b6');
                    theme_g1.addColorStop(1, '#1dc4e9');
                    var theme_g2 = bar.createLinearGradient(0, 100, 0, 0);
                    theme_g2.addColorStop(0, '#899FD4');
                    theme_g2.addColorStop(1, '#A389D4');
                    var data1 = {
                        labels: [0, 1, 2, 3],
                        datasets: [{
                            label: "Data 1",
                            data1: [25, 45, 74, 85],
                            borderColor: theme_g1,
                            backgroundColor: theme_g1,
                            hoverborderColor: theme_g1,
                            hoverBackgroundColor: theme_g1,
                        }, {
                            label: "Data 2",
                            data1: [30, 52, 65, 65],
                            borderColor: theme_g2,
                            backgroundColor: theme_g2,
                            hoverborderColor: theme_g2,
                            hoverBackgroundColor: theme_g2,
                        }]
                    };
                    var myBarChart = new Chart(bar, {
                        type: 'bar',
                        data: data1,
                        options: {
                            barValueSpacing: 10
                        }
                    }); */

                    console.log(chartData);
                    Morris.Bar({
                        element: 'morris-bar-chart',
                        data: chartData,
                        xkey: 'label_name',
                        barSizeRatio: 0.10,
                        barGap: 2,
                        resize: true,
                        responsive: true,
                        ykeys: res.labels,
                        //ykeys: ['count', 'b', 'c'],
                        labels: res.labels,
                        //labels: ['Bar 1', 'Bar 2', 'Bar 3'],
                        barColors: ["0-#1de9b6-#1dc4e9", "0-#899FD4-#A389D4", "#04a9f5"]
                    });
                } else {
                    $('#morris-bar-chart').html('<h4 style="text-align:center">No Record Found</h4>');
                }
            }
        });
    }

    function setChartData1(chartData) {
        $('#morris-bar-chart').html('');
        console.log($('.duration_change').val());
        $.ajax({
            url: '<?= SITE_URL ?>api/dashboard_chart',
            type: 'post',
            data: {
                'type': $('.duration_change').val(),
                'sub_type': $('.package_change').val(),
                'sub_status': $('.package_status').val()
            },
            success: function(res) {
                res = JSON.parse(res);
                if (res.status == 1) {
                    chartData = Object.values(res.data);
                    Morris.Bar({
                        element: 'morris-bar-chart',
                        data: chartData,
                        xkey: 'label_name',
                        barSizeRatio: 0.50,
                        barGap: 2,
                        resize: true,
                        responsive: true,
                        ykeys: ['count'],
                        //ykeys: ['count', 'b', 'c'],
                        labels: ['Total User'],
                        //labels: ['Bar 1', 'Bar 2', 'Bar 3'],
                        //barColors: ["0-#1de9b6-#1dc4e9", "0-#899FD4-#A389D4", "#04a9f5"]
                        barColors: ["0-#1de9b6-#1dc4e9"]
                    });
                } else {
                    $('#morris-bar-chart').html('<h4 style="text-align:center">No Record Found</h4>');
                }
            }
        });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
{{-- <script src="{{ asset('js/chart-morris-custom.js') }}"></script> --}}
@endsection