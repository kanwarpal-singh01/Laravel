@extends('layouts.admin')
@section('content')

<div class="card tableCard code-table">
   <div class="card-header">
      <h5>Notifications List</h5>
      <div class="card-header-right">
      </div>
   </div>
   <!-- <div class="card-header">
            {{ Form::open(array('url' => 'admin/notifications', 'class'=>"form-inline md-form mr-auto mb-4", 'method' => 'get')) }}
            <div class="input-group">
                <input class="form-control" value="<?php if(!empty($query)) { echo $query; } ?>" placeholder='Search' name="q" type="text">  
                <div class="input-group-append">
                    <button class="input-group-text submitSearchForm float-left" type="submit"><i class="feather icon-search"></i></button>
                </div>
            </div>
            {{ Form::close() }}
        </div> -->
   @include('includes.flash')
   <div class="card-block pb-0">
      <div class="table-responsive">
         <table class="table table-hover table-list">
            <thead>
             <tr>
               <th>Image</th>
               <th>Name</th>
               <th>Time</th>
               <th>Message</th>
            </tr>
         </thead>
         <tbody>




         <?php

 if(count($records)>0)
 {
     $color="black";
     $color1="#F1F1F1";
     foreach ($records as $key => $value) { 
         if($value->is_read == 0){$color="White";
         $color1="#f66e27";
     }else{
         $color="black";
         $color1="#F1F1F1";
     }
         ?>


<tr class="gradeY" style="background: {{$color1}};border-width: 2px;border-color: white;">
              <td><a href="{{url('/')}}/admin/chats/chat-messages/{{$value->receiver_id}}?chatid={{$value->id}}" style="color: {{$color}};"><img class="img-radius" src="{{$value->profile_image}}" alt="Generic placeholder image" style="height: 40px;width:40px;border-radius:40px;"></a></td>
              <td><a href="{{url('/')}}/admin/chats/chat-messages/{{$value->receiver_id}}?chatid={{$value->id}}" style="color: {{$color}};"><p><strong style="color: {{$color}};">{{$value->user_name}} : {{$value->receiver_name}}</strong></p> </a></td>
              <td>  <p><span class="n-time" style="color: {{$color}};"><i class="icon feather icon-clock m-r-10"></i>{{date("h:i A", strtotime($value->created_at))}}</span></p></td>
              <td >
              <p style="color: {{$color}};">{{$value->message}}</p>
            </td>
         </tr>


     
    <?php }
 } ?> 



    
         
   
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
