<?php $url = Request::segments(); ?>
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
                <li class="breadcrumb-item active">Edit</li>
        </ol>
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
                    <h4 class="header-title m-t-0 m-b-30">Edit</h4></div>
                    <div class="card-body">
                     {!! Form::model($record, [
            'method' => 'PATCH',     
            'id' => 'customerform',
            'files' => true ,
            'class' => 'form-horizontal',
            'url' => ['admin/'.$url[1].'/edit', base64_encode($record->id)]
            ]) !!}


         
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <input type="hidden"  value="" id="lattitude">
            <input type="hidden" value="" id="longitude">
            <div class="control-group">
               <label for="first_name">Tilte <span class="red">*</span></label>
               <div class="controls">
                  {!! Form::text('title', null, ['class' => 'form-control' ,'placeholder' => 'Enter User Name']) !!}
                  
               </div>
            </div>

               <div class="control-group">
                <label for="email">Price</label>
                <div class="controls">
                  {!! Form::text('price', null, ['class' => 'form-control'  ,'id'=>"price",'placeholder' => 'Enter Price']) !!}
                </div>
               </div>





              

            

               
            
            
            
            
            <br> 
               <div class="control-group float-left">
                  <div class="form-actions text-center">
                     <input type="submit" value="Update " class="btn btn-success">
                  </div>
               </div>
               {!! Form::close() !!}
                
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
   if ($("#customerform").length > 0) {
    $.validator.addMethod(
            "regex",
            function(value, element, regexp) 
            {
                if (regexp.constructor != RegExp)
                    regexp = new RegExp(regexp);
                else if (regexp.global)
                    regexp.lastIndex = 0;
                return this.optional(element) || regexp.test(value);
            },
            "Please check your input."
    );
    $("#customerform").validate({
     
      rules: {

title: {
  required: true,
  maxlength: 55,
  regex:/^[_A-z0-9]*((-|\s)*[_A-z0-9])*$/
},

price: {
    required: true,
    digits: true,
    maxlength: 5,
},



},
messages: {
 
 
title: {
  required: true,
  maxlength: "max 55 character allow",

},

price: {

    maxlength: "max 5 digit allow",
},


},
      
    })
  }
</script>
@stop
