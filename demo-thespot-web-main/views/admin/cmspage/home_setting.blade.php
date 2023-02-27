<?php $url = Request::segments(); ?>
@extends('layouts.admin')
@section('content')
<br>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>HomePage Setting</h1>     
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Dashboard</a></li>
          <li class="breadcrumb-item "><a href="{{url('admin/home-page-setting')}}">HomePage Setting</a></li>
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
                    <h4 class="header-title m-t-0 m-b-30">Slider Setting</h4></div>
                    <div class="card-body">
                    {!! Form::model($record, [
               'method' => 'PATCH',     
               'id' => 'cmspageAddFrom',
               'files' => true ,
               'class' => 'form-horizontal',
               'url' => ['admin/homepage/update', base64_encode($record->id)]
               ]) !!}
               <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
               <div class="control-group">
                  <label for="first_name">Title 1 <span class="red">*</span></label>
                  <div class="controls">
                     {!! Form::text('slider_title_1', null, ['class' => 'form-control ' ,'placeholder' => 'Enter Title here']) !!}
                  </div>
               </div>

                   
               <div class="control-group">
                  <label for="first_name"> Description 1</label>
                  <div class="controls">
                     {!! Form::textarea('slider_description_1',null, ['class' => 'form-control span9 edittextarea' ,'placeholder' => 'Enter Meta Description']) !!}
                  </div>
               </div>



               <div class="control-group">
                  <label for="first_name">Title 2<span class="red">*</span></label>
                  <div class="controls">
                     {!! Form::text('slider_title_2', null, ['class' => 'form-control ' ,'placeholder' => 'Enter Title here']) !!}
                  </div>
               </div>

                   
               <div class="control-group">
                  <label for="first_name"> Description 2 </label>
                  <div class="controls">
                     {!! Form::textarea('slider_description_2',null, ['class' => 'form-control span9 edittextarea' ,'placeholder' => 'Enter Meta Description']) !!}
                  </div>
               </div>




               <div class="control-group">
                  <label for="first_name">Title 3<span class="red">*</span></label>
                  <div class="controls">
                     {!! Form::text('slider_title_3', null, ['class' => 'form-control ' ,'placeholder' => 'Enter Title here']) !!}
                  </div>
               </div>

                   
               <div class="control-group">
                  <label for="first_name"> Description 3 </label>
                  <div class="controls">
                     {!! Form::textarea('slider_description_3',null, ['class' => 'form-control span9 edittextarea' ,'placeholder' => 'Enter Meta Description']) !!}
                  </div>
               </div>


                    <br>
               <div class="card-header">
                    <h4 class="header-title m-t-0 m-b-30">WHO WE ARE SETTING</h4></div>


                    <div class="control-group">
                  <label for="first_name">Title 1 <span class="red">*</span></label>
                  <div class="controls">
                     {!! Form::text('who_title_1', null, ['class' => 'form-control' ,'placeholder' => 'Enter Title here']) !!}
                  </div>
               </div>

                   
               <div class="control-group">
                  <label for="first_name"> Description 1</label>
                  <div class="controls">
                     {!! Form::textarea('who_description_1',null, ['class' => 'form-control span9 edittextarea' ,'placeholder' => 'Enter Meta Description']) !!}
                  </div>
               </div>



               <div class="control-group">
                  <label for="first_name">Title 2<span class="red">*</span></label>
                  <div class="controls">
                     {!! Form::text('who_title_2', null, ['class' => 'form-control ' ,'placeholder' => 'Enter Title here']) !!}
                  </div>
               </div>

                   
               <div class="control-group">
                  <label for="first_name"> Description 2 </label>
                  <div class="controls">
                     {!! Form::textarea('who_description_2',null, ['class' => 'form-control span9 edittextarea' ,'placeholder' => 'Enter Meta Description']) !!}
                  </div>
               </div>


               <div class="card-header">
                    <h4 class="header-title m-t-0 m-b-30">HOW IT WORKS SETTING</h4></div>


                    <div class="control-group">
                  <label for="first_name">Title 1 <span class="red">*</span></label>
                  <div class="controls">
                     {!! Form::text('how_title_1', null, ['class' => 'form-control ' ,'placeholder' => 'Enter Title here']) !!}
                  </div>
               </div>

                   
               <div class="control-group">
                  <label for="first_name"> Description 1</label>
                  <div class="controls">
                     {!! Form::textarea('how_description_1',null, ['class' => 'form-control span9 edittextarea' ,'placeholder' => 'Enter Meta Description']) !!}
                  </div>
               </div>



               <div class="control-group">
                  <label for="first_name">Title 2<span class="red">*</span></label>
                  <div class="controls">
                     {!! Form::text('how_title_2', null, ['class' => 'form-control ' ,'placeholder' => 'Enter Title here']) !!}
                  </div>
               </div>

                   
               <div class="control-group">
                  <label for="first_name"> Description 2 </label>
                  <div class="controls">
                     {!! Form::textarea('how_description_2',null, ['class' => 'form-control span9 edittextarea' ,'placeholder' => 'Enter Meta Description']) !!}
                  </div>
               </div>

               <div class="control-group">
                  <label for="first_name">Title 3<span class="red">*</span></label>
                  <div class="controls">
                     {!! Form::text('how_title_3', null, ['class' => 'form-control ' ,'placeholder' => 'Enter Title here']) !!}
                  </div>
               </div>

                   
               <div class="control-group">
                  <label for="first_name"> Description 3 </label>
                  <div class="controls">
                     {!! Form::textarea('how_description_3',null, ['class' => 'form-control span9 edittextarea' ,'placeholder' => 'Enter Meta Description']) !!}
                  </div>
               </div>

               <div class="control-group">
                  <label for="first_name">Title 4<span class="red">*</span></label>
                  <div class="controls">
                     {!! Form::text('how_title_4', null, ['class' => 'form-control ' ,'placeholder' => 'Enter Title here']) !!}
                  </div>
               </div>

                   
               <div class="control-group">
                  <label for="first_name"> Description 4 </label>
                  <div class="controls">
                     {!! Form::textarea('how_description_4',null, ['class' => 'form-control span9 edittextarea' ,'placeholder' => 'Enter Meta Description']) !!}
                  </div>
               </div>



               <br>
               <div class="card-header">
                    <h4 class="header-title m-t-0 m-b-30">Footer Address</h4></div>


                

                   
               <div class="control-group">
                  <label for="first_name"> Footer Address</label>
                  <div class="controls">
                     {!! Form::textarea('footer_address',null, ['class' => 'form-control span9 edittextarea' ,'placeholder' => 'Enter Address']) !!}
                  </div>
               </div>



            


               <div class="card-header">
                    <h4 class="header-title m-t-0 m-b-30">Contact Setting</h4></div>


              
               <div class="control-group">
                  <label for="first_name"> Website</label>
                  <div class="controls">
                     {!! Form::textarea('contact_website',null, ['class' => 'form-control span9 edittextarea' ,'placeholder' => 'Enter website details']) !!}
                  </div>
               </div>


               <div class="control-group">
                  <label for="first_name"> Call Us</label>
                  <div class="controls">
                     {!! Form::textarea('contact_call_us',null, ['class' => 'form-control span9 edittextarea' ,'placeholder' => 'Enter Phone Detail']) !!}
                  </div>
               </div>


                   
               <div class="control-group">
                  <label for="first_name"> Email</label>
                  <div class="controls">
                     {!! Form::textarea('contact_email',null, ['class' => 'form-control span9 edittextarea' ,'placeholder' => 'Enter Email List']) !!}
                  </div>
                </div>

                <div class="control-group">
                  <label for="first_name"> Map code</label>
                  <div class="controls">
                     {!! Form::textarea('contact_map',null, ['class' => 'form-control span9 ' ,'placeholder' => 'Enter Map Code']) !!}
                  </div>
                </div>


              <br>
                <div class="control-group">
              
              

            <div class="control-group float-left">
               <div class="form-actions span9 text-center">
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
   if ($("#cmspageAddFrom").length > 0) {
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
    $("#cmspageAddFrom").validate({
     
    
            rules: {
                title_en: {
                    required: true,
                },

                meta_description_en: {
                    required: true,
                },
 
                content_en: {
                    required: true,
                },
                
 
            },
            messages: {
 
                title_en: {
                    required: "Please enter title.", 

                },
                meta_description_en: {
                    required: "Please enter meta description.",
                },
                content_en: {
                    required: "Please enter description.",
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
 <script type="text/javascript">
  var ranges = [
  '\ud83c[\udf00-\udfff]', // U+1F300 to U+1F3FF
  '\ud83d[\udc00-\ude4f]', // U+1F400 to U+1F64F
  '\ud83d[\ude80-\udeff]'  // U+1F680 to U+1F6FF
];

setTimeout(removeInvalidChars, 100);
function removeInvalidChars() {
  var str = $('.valid-text').val();
 
  str = str.replace(new RegExp(ranges.join('|'), 'g'), '');
  $(".valid-text").val(str);
 setTimeout(removeInvalidChars, 100);
}
</script>
@stop
