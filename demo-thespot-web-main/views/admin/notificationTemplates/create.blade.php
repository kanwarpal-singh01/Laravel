@extends('layouts.newadmin')
@section('content')
<div id="content-header">
   <div id="breadcrumb"> <a href="{{ url('/admin/dashboard') }}" title="Go to Dashboard" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="{{url('/')}}/admin/emailtemplate" >Email Templates</a> <a href="javascript:;" class="current"> Add</a></div>
   <h1>Add Email Template</h1>
</div>
<div class="container-fluid">
   @include('includes.flashMessage')
   <div class="row-fluid">
      <div class="span12">
         <div class="widget-box">
            <div class="widget-title"> <span class="icon"> Email Template</span>
            </div>
            <div class="widget-content nopadding">
               {!! Form::open(array('url' => 'admin/emailtemplate/store','id' => 'cmspageAddFrom','class' => 'form-horizontal' )) !!}
               <input type="hidden" name="_token" value="{{ csrf_token() }}">
               <div class="control-group">
                  {!! Form::label('author', 'Title', ['class' => 'control-label']) !!}
                  <div class="controls">
                     {!! Form::text('title', null, ['class' => 'form-control span9' ,'placeholder' => 'Enter Title']) !!}
                  </div>
               </div>
               
               <div class="control-group">
                  {!! Form::label('author', 'Subject', ['class' => 'control-label']) !!}
                  <div class="controls">
                     {!! Form::text('subject', null, ['class' => 'form-control span9' ,'placeholder' => 'Enter Subject']) !!}
                  </div>
               </div>
               
               <div class="control-group">
                  {!! Form::label('author', 'Content', ['class' => 'control-label']) !!}
                  <div class="controls">
                     {!! Form::textarea('content',null, ['class' => 'form-control span8' ,'placeholder' => 'Enter Content','id'=>'editor1']) !!}
                  </div>
               </div>
               
                <div class="control-group">
               <div class="control-group">
               <div class="form-actions span9 text-center">
                  <input type="submit" value="Save " class="btn btn-success btn-submit">
               </div>
            </div>
               {!! Form::close() !!}
            </div>
         </div>
      </div>
   </div>
</div>
@stop
@section('customscript')
<script src="{{ url('/') }}/front/js/ckeditor/ckeditor.js"></script> 
<script type="text/javascript" >
   // Replace the <textarea id="editor1"> with a CKEditor
   // instance, using default configuration.
   CKEDITOR.replace('editor1');
   CKEDITOR.replace('editor2');
</script>
@stop

