<?php $url = Request::segments(); ?>
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
<div class="container-fluid">
@include('includes.flashMessage')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card"><br>
                    <div class="card-header">
                    <h4 class="header-title m-t-0 m-b-30">Add</h4></div>
                    <div class="card-body">
                    {!! Form::open(array('url' => 'admin/cmspage/store','id' => 'cmspageAddFrom1','class' => 'form-horizontal' )) !!}
               <input type="hidden" name="_token" value="{{ csrf_token() }}">
               <div class="control-group">
                  {!! Form::label('author', 'Title', ['class' => 'control-label']) !!}
                  <div class="controls">
                     {!! Form::text('title_en', null, ['class' => 'form-control span9' ,'placeholder' => 'Enter Title']) !!}
                  </div>
               </div>


               
               <div class="control-group">
                  {!! Form::label('meta_description', 'Meta Description', ['class' => 'control-label']) !!}
                  <div class="controls">
                     {!! Form::textarea('meta_description_en',null, ['class' => 'form-control span9 ckeditor' ,'placeholder' => 'Enter Meta Description']) !!}
                  </div>
               </div>

               
               <div class="control-group">
                  {!! Form::label('author', 'Description', ['class' => 'control-label']) !!}
                  <div class="controls">
                     {!! Form::textarea('content_en',null, ['class' => 'form-control span9 ckeditor' ,'placeholder' => 'Enter Content']) !!}
                  </div>
               </div>

            
               <br>
               <div class="control-group float-left">
               <div class="control-group">
                  <div class="form-actions span9 text-center">
                     <input type="submit" value="Save" class="btn btn-success" style="margin-right: 20px;">
                  </div>
               </div>
               {!! Form::close() !!}
                
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC9BEiEwu90PwsPsENmtvp5p26K3A3UqVQ&libraries=places" type="text/javascript"></script>
<script src="{{asset('js/address.js')}}"></script>
@stop
