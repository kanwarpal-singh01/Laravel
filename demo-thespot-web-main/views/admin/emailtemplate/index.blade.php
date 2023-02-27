@extends('layouts.admin')
@section('content')
<div class="card tableCard code-table">
  <div class="card-header">
    <h5>Email Management</h5>
  </div>
</div>
<div class="card tableCard code-table">
   <div class="card-header">
      <h5>Email Templates</h5>
      <div class="card-header-right">
      </div>
   </div>
   <div class="card-header">
            {{ Form::open(array('url' => 'admin/emailtemplate', 'class'=>"form-inline md-form mr-auto mb-4", 'method' => 'get')) }}
            <div class="input-group">
                <input class="form-control" value="<?php if(!empty($query)) { echo $query; } ?>" placeholder='Search' name="q" type="text">  
                <div class="input-group-append">
                    <button class="input-group-text submitSearchForm float-left" type="submit"><i class="feather icon-search"></i></button>
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
               <th>Title</th>
               <th>Subject</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            <?php $i = 1;  if(count($records)>0)
            { 
             $pagelimit = config("Settings.AdminPageLimit");
             $page = $records->currentPage() ;
             $i = ($page * $pagelimit) - ($pagelimit);

             ?>
             @foreach ($records as $row)
             @if($row->status == 1 )
             <?php $i++ ?>
             <tr class="gradeY">
              <td>{{$i}}</td>
              <td>{{ucfirst($row->title)}}</td>
              <td>{{($row->subject)}}</td>
              <td class="center">

               <a  href="{{ url('/admin/emailtemplate/edit') }}/{{base64_encode($row->id)}}"  ><i class="fa fa-edit"></i></a>
            </td>
         </tr>
               @endif
         @endforeach
      <?php } else { ?>
        <tr>
         <td colspan='5'> Record Not Found</td>
      </tr>
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