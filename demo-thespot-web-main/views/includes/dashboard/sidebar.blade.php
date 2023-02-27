<?php 

 $user=Auth::user();
 $role=$user->role;
 $plan=$user->plan;
?>
<ul class="navbar-nav sidebar accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand text-center" href="{{ url('/') }}">
  <div class="sidebar-brand-text"><img src="{{ config('app.asset_url')}}assets/img/dash-icon.png"></div>
</a>

<div class="sidebar-menu-inside">
  <!-- Nav Item - Dashboard -->

  @if($role=='recruiter' || $role=='employer' ||  $role=='sub-recruiter'  )
  <li class="nav-item @if(Request::path()=='account-setting')active @endif">
    <a class="nav-link" href="{{ url('/') }}/account-setting">
      <i><img src="{{ config('app.asset_url')}}assets/img/siderbar-1.png"></i>
      <span>Account Setting</span></a>
  </li>

  <li class="nav-item @if(Request::path()=='notification')active @endif">
    <a class="nav-link" href="{{ url('/') }}/notification">
      <i><img src="{{ config('app.asset_url')}}assets/img/siderbar-2.png"></i>
      <span>Notification Setting</span></a>
  </li>


  <li class="nav-item @if(Request::path()=='save-payment-detail')active @endif">
    <a class="nav-link" href="{{ url('/') }}/save-payment-detail">
      <i><img src="{{ config('app.asset_url')}}assets/img/siderbar-3.png"></i>
      <span>Payment Setting</span></a>
  </li>

  <li class="nav-item @if(Request::path()=='messages')active @endif" >
    <a class="nav-link" href="{{ url('/') }}/messages">
      <i><img src="{{ config('app.asset_url')}}assets/img/siderbar-4.png"></i>
      <span>Chat Board</span></a>
  </li>

<?php

$employer_credit_status=Helper::employer_credit_status();

?>
@if($employer_credit_status )

<li class="nav-item @if(Request::path()=='creadits')active @endif">
    <a class="nav-link" href="{{ url('/') }}/creadits">
      <i><img src="{{ config('app.asset_url')}}assets/img/siderbar-5.png"></i>
      <span>Credits</span></a>
  </li>

@endif

@endif  

@if( $role=='employer')
<li class="nav-item @if(Request::path()=='jobs')active @endif">
    <a class="nav-link " href="{{ url('/') }}/jobs">
      <i><img src="{{ config('app.asset_url')}}assets/img/siderbar-6.png"></i>
      <span>Job Post</span></a>
  </li>
@endif


@if( $role=='recruiter' ||  $role=='sub-recruiter' )

<li class="nav-item @if(Request::path()=='explore-job')active @endif">
    <a class="nav-link" href="{{ url('/') }}/explore-job">
      <i><img src="{{ config('app.asset_url')}}assets/img/siderbar-6.png"></i>
      <span>Explore Job</span></a>
  </li>
<li class="nav-item @if(Request::path()=='hiring-history')active @endif">
    <a class="nav-link" href="{{ url('/') }}/hiring-history">
      <i><img src="{{ config('app.asset_url')}}assets/img/siderbar-10.png"></i>
      <span>Hiring History</span></a>
  </li>

  <li class="nav-item @if(Request::path()=='payment-history')active @endif">
    <a class="nav-link" href="{{ url('/') }}/payment-history">
      <i><img src="{{ config('app.asset_url')}}assets/img/siderbar-5.png"></i>
      <span>Payment History</span></a>
  </li>

@endif



@if( $role=='recruiter' )


  
  <li class="nav-item @if(Request::path()=='subrecruiter')active @endif">
    <a class="nav-link" href="{{ url('/') }}/subrecruiter">
      <i><img src="{{ config('app.asset_url')}}assets/img/siderbar-1.png"></i>
      <span>Manage Sub Recruiter</span></a>
  </li>
  <li class="nav-item @if(Request::path()=='view-activity')active @endif" >
    <a class="nav-link" href="{{ url('/') }}/view-activity">
      <i><img src="{{ config('app.asset_url')}}assets/img/siderbar-9.png"></i>
      <span>Sub Recruiter Activity</span></a>
  </li>


@endif





  <li class="nav-item">
    <a class="nav-link" href="{{ url('/') }}/logout"  data-toggle="modal" data-target="#logoutModal">
      <i><img src="{{ config('app.asset_url')}}assets/img/siderbar-7.png"></i>
      <span>Logout</span></a>
  </li>

</div>
</ul>
<!-- End of Sidebar -->