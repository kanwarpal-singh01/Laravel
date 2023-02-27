@extends('layouts.admin')
@section('content')
<div class="card tableCard code-table">
   <div class="card-header">
      <h5>Chat Management</h5>
      <div class="card-header-right">
      </div>
   </div>
</div>
<div class="card tableCard code-table">
   <div class="card-header">
      <h5>Fake Users</h5>
   </div>
   <div class="card-block" style="margin-top:20px">
      @foreach($chatsList as $key => $value)
      <a href="{{route('chatting',['id'=>$value->id])}}">
         <div class="to-do-list mb-3" style="border: 1px solid #f1f4f7;padding: 10px 10px 0px;">
            <div class="checkbox-fade fade-in-default">
               <label class="check-task">
                  <div class="row">
                     <div class="col-auto">
                        <img class="rounded-circle" style="width:40px;height:40px;" src="{{config('app.profile_url')}}/{{$value->profile_image}}" alt="chat-user">
                     </div>
                     <div class="col">
                        <h6>{{$value->name}}</h6>
                     </div>
                  </div>
               </label>
            </div>
         </div>
      </a>
      @endforeach
   </div>
   @include('includes.flash')
</div>
@stop