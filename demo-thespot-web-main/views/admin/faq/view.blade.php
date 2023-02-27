
<?php $url = Request::segments();?>
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
    <h5>FAQ Detail</h5>
    <div class="card-header-right">
      <a class="btn badge-danger" href="javascript:window.history.back();"><i class="fa fa-arrow-left"></i> Back</a>
    </div>
  </div>
  @include('includes.flash')
  <div class="card-body">
      <h6>Question</h6>
      <div class="d-flex justify-content-start">
        {{$record->question_en}}
      </div>
      <hr>
      <h6>Answer</h6>
      <div class="d-flex justify-content-start">
        {{html_entity_decode($record->answer_en)}}
      </div>
      <hr>
      <h6>Question Arbic</h6>
      <div class="d-flex justify-content-start">
        {{html_entity_decode($record->question_ar)}}
      </div>
      <hr>
      <h6>Answer Arbic</h6>
      <div class="d-flex justify-content-start">
        {{html_entity_decode($record->answer_ar)}}
      </div>
    </div>
</div>
@stop
