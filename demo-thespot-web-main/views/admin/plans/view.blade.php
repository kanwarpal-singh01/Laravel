
<?php $url = Request::segments();?>
@extends('layouts.newadmin')
@section('content')
<br>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>{{ucfirst($url[1])}}</h1>     
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Dashboard</a></li>
          <li class="breadcrumb-item "><a href="{{url('admin/'.$url[1])}}">{{ucfirst($url[1])}}</a></li>
                <li class="breadcrumb-item active">View</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
               <div class="col-md-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
                    <h4 class="header-title m-t-0 m-b-30">View</h4></div>



            <div class="card-body box-profile">
              <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                  <b>User Name</b> <a class="float-right">{{ $record->user_name ? $record->user_name:"No data"}}</a>
                </li>
              
                
                <li class="list-group-item">
                  <b>Email</b> <a class="float-right">{{$record->email}}</a>
                </li>

@if($record->role=='recruiter')
<li class="list-group-item">
                  <b>Agency Name</b> <a class="float-right">{{$record->agency_name}}</a>
                </li>

                <li class="list-group-item">
                  <b>Agency Description</b> <a class="float-right" style="width: 50%;     text-align: right;">{{$record->agency_description}}</a>
                </li>
@else
<li class="list-group-item">
                  <b>Firm Name</b> <a class="float-right">{{$record->firm_name}}</a>
                </li>

                <li class="list-group-item">
                  <b>Company Description</b> <a class="float-right" style="width: 50%;text-align: right;word-break: break-word;">{{$record->company_description}}</a>
                </li>
               

@endif
              

                <li class="list-group-item">
                  <b>Phone</b> <a class="float-right">{{$record->phone}}</a>
                </li>

                <li class="list-group-item">
                  <b>Role</b> <a class="float-right">{{$record->role}}</a>
                </li>
            
                <li class="list-group-item">
                  <b>Uploaded Documents</b> 
                                        @foreach($documents as $doc)
                                    
                                         <div> 
                                        <img src="https://img.icons8.com/ios/50/000000/file.png"/>  <a href="{{ config('app.asset_url')}}uploads/{{ $record->role }}/doc/{{ $doc->link }}" target="_blank" style="color:#f9582b;">{{ $doc->link}}</a>
                                        </div>
                                        

                                        @endforeach
                                   
                </li>
            
                
                
                
                
              </ul>
            </div>
            <!-- /.card-body -->
          </div>
        </div>
              
            </div>
        </div>
    </div>
</div>

<style>
  /* .widget-title, .modal-header, .table th, div.dataTables_wrapper .ui-widget-header{
   background:#2e363f;
   }
   .table-icon span { color:#fff; }
   .table-icon h5 { color:#fff; }*/
   table, th, td {
   border: 1px black;
   }
   .table-user-information tr td:first-child { 
   font-weight:bold ;
   width:150px;
   position:relitive;
   }
   .table-user-information tr td{
   border-bottom:1px solid #FFF;
   padding:10px 0px;;
   } 
</style>
@stop
