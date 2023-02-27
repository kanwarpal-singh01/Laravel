
<?php $url = Request::segments();?>
@extends('layouts.newadmin')
@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>{{ucfirst($url[1])}}</h1>     
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
                  <b>Title</b> <a class="float-right">{{$record->title_en}}</a>
                </li>
                <li class="list-group-item">
                  <b>Description</b> <a class="float-right"><?php echo $record->content_en ;?></a>
                </li>
                
                <li class="list-group-item">
                  <b>Slug</b> <a class="float-right"><?php echo $record->slug ;?></a>
                </li>
                <li class="list-group-item">
                  <b>Meta Description</b> <a class="float-right"><?php echo $record->meta_description_en ;?></a>
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
