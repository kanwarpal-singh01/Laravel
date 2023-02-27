

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
          <li class="breadcrumb-item "><a href="{{url('admin/faq')}}">{{ucfirst($url[1])}}</a></li>
                <li class="breadcrumb-item active">Add</li>
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
                    <h4 class="header-title m-t-0 m-b-30">Add</h4></div>
                    <div class="card-body">
                   {!! Form::open(array('url' => 'admin/'.$url[1] ,'id' => 'faqAddFrom','class' => 'form-horizontal' )) !!}
                          
                        <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
          

               <div class="control-group">
                <label for="email">Email <span class="red">*</span></label>
                <div class="controls">
                  {!! Form::text('email', null, ['class' => 'form-control span6' ,'placeholder' => 'Enter Email']) !!}
                </div>
               </div>

                <div class="control-group">
                <label for="email">Message <span class="red">*</span></label>
                <div class="controls">
                {!! Form::textarea('content', null, ['class' => 'form-control span9 edittextarea' ,'placeholder' => 'Enter Content','required']) !!}
                </div>
               </div>


               


               <br>
               <div class="control-group float-left">
                  <div class="form-actions span6 text-center">
                     <input type="submit" value="Add " class="btn btn-success">
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
   if ($("#faqAddFrom").length > 0) {
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
    $("#faqAddFrom").validate({
     
    
      rules: {
          
 
                email: {
                    required: true,
                    maxlength: 50,
                    email: true,
                },
                content: {
                  required: true,
                },
 
            },
            messages: {
 
             
              
                email: {
                    required: "Please enter valid email",
                    email: "Please enter valid email",
                    maxlength: "The email name should less than or equal to 50 characters",
                },
                content: {
                  required: "Please enter content.", 
                },
 
            },
        
    })
  }
</script>

<script src="https://cdn.tinymce.com/4/tinymce.min.js"></script>
  <script>
   tinymce.init({ selector:'.edittextarea', height: 200,  plugins: [
    'advlist autolink lists link print preview anchor',
    'visualblocks code fullscreen',
    'insertdatetime table contextmenu paste code'
   ], });
 </script>
@stop



