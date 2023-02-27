<?php $url = Request::segments();?>
@extends('layouts.newadmin')
@section('content')
<br>
<div class="content">
  <section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>{{ucfirst($url[1])}} </h1>     
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Dashboard</a></li>
          
                <li class="breadcrumb-item active">{{ucfirst($url[1])}} List</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
    <div class="container-fluid">
      @include('includes.flashMessage')
        <div class="row">
            <div class="col-12">
                <div class="card-box"><br/>
                    
                    <!-- <a  href="{{ url('admin/'.$url[1].'/create?role=') }}{{Request::input('role')}}" style="float: right;" class=" btn btn-success add-new">Add New</a> -->
                    {{ Form::open(array('url' => 'admin/'.$url[1], 'class'=>"form-inline md-form mr-auto mb-4", 'method' => 'get')) }}
                    <!-- <input class="form-control mr-sm-2" value="<?php if(!empty($query)) { echo $query; } ?>" placeholder='Search' name="q" type="text">  
                    <button class="submitSearchForm float-left" type="submit"><i class="fa fa-search"></i></button> -->
                     {{ Form::close() }}
                    <div class="table-rep-plugin">
                        <div class="table-responsive" data-pattern="priority-columns">
                            
                                 <table class="table table-bordered data-table">
            <thead>
              <tr>
                <th>S.No.</th>
                <th>Title</th>
                
                <th>Price</th>
         
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            <?php 
            $i = 1;  
            if(count($model)>0)
            { 
              $page = $model->currentPage();
              $pagelimit = config("Settings.AdminPageLimit");
              $i = ($page * $pagelimit) - ($pagelimit); ?>
              @foreach ($model as $row)
                <?php $i++ ?>
                <tr class="gradeY">
                  <td>{{$i}}</td>
                  <td>{{$row->title}}</td>
                
                  <td>{{Helper::get_config('currency')}} {{$row->price}}</td>
            
                  <td class="center">
                    <a  href="{{ url('/admin/'.$url[1].'/edit') }}/{{base64_encode($row->id)}}"  class="mb-2 mr-2"><img style="width:24px;" src="{{ config('app.asset_url')}}/admin/icon/edit.png"/></a>
                  
                  
                    <!-- <a  href="{{ url('/admin/'.$url[1].'/delete') }}/{{base64_encode($row->id)}}/{{base64_encode($url[1])}}"  class="mb-2 mr-2 btn btn-danger" onclick="return confirm('Want to Delete ?')" ><i class="icon-trash"></i> Delete</a>
                      -->
                  </td>
                </tr>
              @endforeach
      <?php } 
            else 
            { ?>
              <tr>
                <td colspan='5'>No Result Found</td>
              </tr>
      <?php } ?>
            </tbody>
          </table><br>
                        </div>
                       <?php if(!empty($model)) { ?>
        <div class="pagination-parent">
          <div class="left">
          Total Records:<strong>{{$model->total()}}</strong> Page of pages: <strong>{{$model->currentPage()}}</strong> of <strong>{{$model->lastPage()}}</strong> 
          </div>
          <div class="pagination"> 
            {{  $model->appends(Request::only('q'))->links() }} 
          </div>
        </div>
      <?php } ?> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('customscript')
<script>
$(".select_checkbox").click(function()
{
  if($(".select_checkbox").length == $(".select_checkbox:checked").length)
  {
    $(".status_checkbox").prop("checked", "checked");
  }
  else
  {
    $(".status_checkbox").removeAttr("checked");
  }            
});   
</script>

@stop


