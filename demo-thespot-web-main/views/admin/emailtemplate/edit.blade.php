<?php $url = Request::segments(); ?>
@extends('layouts.admin')
@section('content')
<div class="card tableCard code-table">
  <div class="card-header">
    <h5>Email Management</h5>
  </div>
</div>
@include('includes.flash')
<div class="card tableCard code-table">
  <div class="card-header">
    <h5>Edit Email Template</h5>
    <div class="card-header-right">

    </div>
  </div>
  <div class="card-body">
    {!! Form::model($record, [
    'method' => 'PATCH',
    'id' => 'customerform',
    'files' => true ,
    'class' => 'form-horizontal',
    'url' => ['admin/emailtemplate/update',base64_encode($record->id)]
    ]) !!}
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="control-group">
      <label for="first_name">Title <span class="red">*</span></label>
      <div class="controls">
        {!! Form::text('title', null, ['class' => 'form-control valid-text' ,'placeholder' => 'Enter Title']) !!}
      </div>
    </div>

    <div class="control-group">
      <label for="first_name">Subject <span class="red">*</span></label>
      <div class="controls">
        {!! Form::text('subject', null, ['class' => 'form-control span9' ,'placeholder' => 'Enter Subject']) !!}
      </div>
    </div>

    <div class="control-group">
      <label for="first_name">Content <span class="red">*</span></label>
      <div class="controls">
        {!! Form::textarea('content', null, ['class' => 'form-control span9','id'=>'edittextarea' ,'placeholder' => 'Enter Content','required']) !!}
      </div>
    </div>
    <br>
    <div class="control-group">

      <div class="control-group float-left">
        <div class="form-actions span9 text-center">
          <input type="submit" value="Update" class="btn btn-success">
        </div>
      </div>
      {!! Form::close() !!}

    </div>
  </div>
</div>

@stop
@section('customJs')
<script src="{{asset('/plugins/ckeditor/ckeditor.js')}}"></script>
<script>
  CKEDITOR.replace('edittextarea', {
    width: '100%',
  });
  if ($("#customerform").length > 0) {
    $.validator.addMethod(
      "regex",
      function(value, element, regexp) {
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
          regex: /^[_A-z0-9]*((-|\s)*[_A-z0-9])*$/
        },

        subject: {
          required: true,
          maxlength: 55,
          regex: /^[_A-z0-9]*((-|\s)*[_A-z0-9])*$/
        },

        slug: {
          required: true,
        },
        content: {
          required: true,
        },


      },
      messages: {

        title: {
          required: "Please enter title of template.",
          maxlength: "Max 55 character allow"

        },
        subject: {
          required: "Please enter subject.",
          maxlength: "Max 55 character allow"
        },
        slug: {
          required: "Please enter slug.",
        },
        content: {
          required: "Please enter content.",
        },


      },
    })
  }
</script>

<script type="text/javascript">
  var ranges = [
    '\ud83c[\udf00-\udfff]', // U+1F300 to U+1F3FF
    '\ud83d[\udc00-\ude4f]', // U+1F400 to U+1F64F
    '\ud83d[\ude80-\udeff]' // U+1F680 to U+1F6FF
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