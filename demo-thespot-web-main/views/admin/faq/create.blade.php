<?php $url = Request::segments(); ?>
@extends('layouts.admin')
@section('content')

<div class="card tableCard code-table">
  <div class="card-header">
    <h5>FAQ Management</h5>
  </div>
</div>
<div class="card tableCard code-table">
  <div class="card-header">
    <h5>New FAQ</h5>
    <div class="card-header-right">
    </div>
  </div>
  @include('includes.flash')
  <div class="card-body">
    {!! Form::open(array('url' => 'admin/faq/store','id' => 'faqAddFrom','class' => 'form-horizontal','data-validate'=>"validate",'autocomplate'=>'off')) !!}
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
          <input type="button" value="Submit" class="btn btn-success" onclick="formValidation('faqAddFrom','submit')">
    </div>
    {!! Form::close() !!}
  </div>
</div>
@stop
@section('customJs')

@stop
