<?php $url = Request::segments(); ?>
@extends('layouts.admin')
@section('content')
<div class="card tableCard code-table">
  <div class="card-header">
    <h5>FAQ Management</h5>
    <div class="card-header-right">
    </div>
  </div>
</div>
<div class="card tableCard code-table">
  <div class="card-header">
    <h5>FAQ Edit</h5>
    <div class="card-header-right">
      <a class="btn badge-danger" href="javascript:window.history.back();"><i class="fa fa-arrow-left"></i> Back</a>
    </div>
  </div>
  @include('includes.flash')
  <div class="card-body">
    {!! Form::model($record, ['method' => 'PATCH','id' => 'faqAddFrom','files' => true ,'class' => 'form-horizontal','url' => ['admin/faq/update', base64_encode($record->id)],'data-validate'=>"validate"]) !!}
    <div class="form-group">
      <label for="last_name">Question <span class="red">*</span></label>
        {!! Form::text('question_en', null, ['class' => 'form-control span9' ,'placeholder' => 'Enter Question here','data-type'=>'notNull','maxlength'=>'255']) !!}
    </div>

    <div class="form-group">
      <label for="last_name">Answer <span class="red">*</span></label>
        {!! Form::text('answer_en', null, ['class' => 'form-control span9' ,'placeholder' => 'Enter Answer here','required','data-type'=>'notNull','maxlength'=>'5000']) !!}
    </div>
    <div class="form-group">
      <label for="last_name">Question Arbic<span class="red">*</span></label>
        {!! Form::text('question_ar', null, ['class' => 'form-control span9' ,'placeholder' => 'Enter Question here','data-type'=>'notNull','maxlength'=>'255']) !!}
    </div>

    <div class="form-group">
      <label for="last_name">Answer Arbic<span class="red">*</span></label>
        {!! Form::text('answer_ar', null, ['class' => 'form-control span9' ,'placeholder' => 'Enter Answer here','required','data-type'=>'notNull','maxlength'=>'5000']) !!}
    </div>
    <br>
    <div class="form-group">
      <input type="button" value="Update " class="btn btn-success" onclick="formValidation('faqAddFrom','submit')">
    </div>
    {!! Form::close() !!}
  </div>
</div>
@stop
@section('customJs')
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
        question: {
          required: true,
          maxlength: 250
        },

        answer: {
          required: true,
          maxlength: 3000
        },




      },
      messages: {

        question: {
          required: "Please enter question.",
          maxlength: "Question must be less than 250 characters only."
        },
        answer: {
          required: "Please enter answer.",
          maxlength: "Answer must be less than 3000 characters only."
        },



      },
    })
  }
</script>
@stop
