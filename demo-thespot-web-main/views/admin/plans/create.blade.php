

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
                   {!! Form::open(array('url' => 'admin/'.$url[1].'/store/'.Request::query('role') ,'id' => 'faqAddFrom','class' => 'form-horizontal' )) !!}
                          
                   <input type="hidden" name="_token" value="{{ csrf_token() }}">
                 <div class="control-group">
               <label for="first_name">Title <span class="red">*</span></label>
               <div class="controls">
                  {!! Form::text('title', null, ['class' => 'form-control' ,'placeholder' => 'Enter Title']) !!}
                  
               </div>
            </div>

            
               <div class="control-group">
                <label for="email">Price <span class="red">*</span></label>
                <div class="controls">
                  {!! Form::text('price', null, ['class' => 'form-control span6' ,'placeholder' => 'Enter Price']) !!}
                </div>
               </div>

                <div class="control-group">
                <label for="email">Credit <span class="red">*</span></label>
                <div class="controls">
                  <input type="text" class="form-control" placeholder="Enter Credit" name="credit"  />
                </div>
               </div>

               @if(Request::query('role')=='employer')
              <div class="control-group">
                <label for="email">Job Limit</label>
                <div class="controls">
                <input class="form-control" name="job" type="text"  placeholder="Enter Job Limit" >
                  
                </div>
               </div>

               <div class="control-group">
                <label for="email">Placement Fees %</label>
                <div class="controls">
                <input class="form-control" name="fees" type="text"  placeholder="Enter Placement Fees" >
                  
                </div>
               </div>

           
                @else

                <div class="control-group">
                                <label for="email">CV Limit</label>
                                <div class="controls">
                                <input class="form-control" name="cv" type="text"  placeholder="Enter CV Limit" >
                                  
                                </div>
                              </div>

                              <div class="control-group">
                <label for="email">Placement Fees %</label>
                <div class="controls">
                <input class="form-control" name="fees" type="text"  placeholder="Enter Placement Fees" >
                  
                </div>
               </div>



                @endif
              
               


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




jQuery.validator.setDefaults({
  debug: true,
  success: "valid"
  
});
$( "#faqAddFrom" ).validate({
   submitHandler: function(form) {
    form.submit();
  },

rules: {

                title: {
                  required: true,
                  maxlength: 55,
                },

                credit: {
                    required: true,
                    digits: true,
                    min:1,
                    maxlength: 3,
                },
                cv: {
                    required: true,
                    digits: true,
                    min:1,
                    maxlength: 3,
                },
                job: {
                    required: true,
                    digits: true,
                    min:1,
                    maxlength: 3,
                },
                price: {
                    required: true,
                    digits: true,
                    maxlength: 5,
                },
                fees:{
                    required: true,
                    digits: true,
                    maxlength: 3,
                },

},

messages: {


}});


});



</script>
@stop





