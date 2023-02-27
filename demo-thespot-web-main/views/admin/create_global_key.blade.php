<?php $url = Request::segments(); ?>
@extends('layouts.admin')
@section('content')
<div id="content-header">
   <div id="breadcrumb"> 
      <a href="{{ url('/admin/dashboard') }}" title="Go to Dashboard" class="tip-bottom"><i class="icon-home"></i> Home</a>
  <a href="{{url('/')}}/admin/config" >Global config</a> 
      <a href="javascript:;" class="current">Add</a>
   </div>
   <h1>Add {{ucfirst($url[1])}}</h1>
</div>
<div class="container-fluid">
   @include('includes.flashMessage')
   <div class="row-fluid">
      <div class="span12">
         <div class="widget-box">
            <div class="widget-title"> <span class="icon">Add</span>
            </div>
            <div class="widget-content nopadding">

        {!! Form::open(array('url' => 'admin/'.$url[1].'/create','id' => 'ProductCategoryAddFrom1','class' => 'form-horizontal', 'files'=> true, 'method' => 'post',    )) !!}


            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <input type="hidden"  value="" id="lattitude">
            <input type="hidden" value="" id="longitude">
            <div class="control-group">
               {!! Form::label('Title', 'Title :', ['class' => 'control-label']) !!}
               <div class="controls">
                  {!! Form::text('title', null, ['class' => 'form-control span6' ,'placeholder' => 'Enter Title']) !!}
               </div>
            </div>

            <div class="control-group">
               {!! Form::label('value', 'Value :', ['class' => 'control-label']) !!}
               <div class="controls">
                  {!! Form::text('value', null, ['class' => 'form-control span6' ,'placeholder' => 'Enter Value']) !!}
               </div>
            </div>
           
            
               <div class="control-group">
                {!! Form::label('slug', 'Slug:', ['class' => 'control-label']) !!}
                <div class="controls">
                  {!! Form::text('slug', null, ['class' => 'form-control span6' ,'placeholder' => 'Enter Slug']) !!}
                </div>
               </div>
            

               <?php $type = array('text'=>"Text", 'number'=>'Number','textarea'=>"Textarea")?>
               {!! Form::label('categorytype', 'Text Type :', ['class' => 'control-label']) !!}
               <div class="controls">
                  {!! Form::select('type',$type,['class' => 'form-control span6']) !!}
               </div>
              

               <?php $option = array('G'=>"General Settings", 'A'=>'Admin Settings','P'=>"Price Settings")?>
               {!! Form::label('categorytype', 'Category Type :', ['class' => 'control-label']) !!}
               <div class="controls ">
                  {!! Form::select('categorytype', $option,['class' => 'form-control span6']) !!}
               </div>
               <div class="control-group">
                  <div class="form-actions span6 text-center">
                     <input type="submit" value="Update " class="btn btn-success">
                  </div>
               </div>
               {!! Form::close() !!}              
            </div>
         </div>
      </div>
   </div>
</div>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC9BEiEwu90PwsPsENmtvp5p26K3A3UqVQ&libraries=places" type="text/javascript"></script>
<script src="{{asset('js/address.js')}}"></script>
@stop


