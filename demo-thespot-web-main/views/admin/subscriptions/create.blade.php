

<?php $url = Request::segments(); ?>
@extends('layouts.admin')
@section('content')

<div class="card tableCard code-table">
   <div class="card-header">
      <h5>Subscription Management</h5>
      <div class="card-header-right">
         <a  href="{{ route('subscriptions') }}" class="btn btn-primary"><i class="fa fa-list"> Subscription List</i></a>
      </div>
   </div>
</div>
<div class="card tableCard code-table">
   <div class="card-header">
      <h5>Add New Subscription</h5>
   </div>
   <div class="card-body">
      {!! Form::open(array('id' => 'new-plan','class' => 'form-horizontal','autocomplete'=>"off",'data-validation'=>'validate')) !!}
      <div class="form-group">
         <label>Plan Name </label>
         {!! Form::text('plan_name', null, ['class' => 'form-control' ,'placeholder' => 'Enter Plan Title','data-type'=>'plan_name']) !!}
      </div>
      <div class="form-group">
         <label>Brief </label>
         {!! Form::textarea('short_brief', null, ['class' => 'form-control' ,'placeholder' => 'Short Description about Plan','rows'=>3,'maxlength'=>350,'data-type'=>'notNull']) !!}
      </div>
      <div class="form-group">
         <label>Price </label>
         {!! Form::text('amount', null, ['class' => 'form-control' ,'placeholder' => 'Subscription Plan Price','data-type'=>'price','maxlength'=>8]) !!}
      </div>
      <div class="form-group">
         <div class="row">
            <div class="col-md-6">
               <label>Plan Duration </label>
               {!! Form::text('duration', null, ['class' => 'form-control' ,'placeholder' => 'Duration Digit accoding to Duration Type','oninput'=>"this.value = this.value.replace(/[a-zA-Z!@#/-/$%;^&*()_+=]+/g, '')",'onpaste'=>"return false;", 'maxlength'=>4,'data-type'=>'notNull']) !!}
            </div>
            <div class="col-md-6">
               <label>Duration Type</label>
               {!! Form::select('duration_type',['Days'=>'Days','Month'=>'Month','Year'=>'Year'],'Month', ['class' => 'form-control' ,'placeholder' => 'Select Plan Duration','data-type'=>'notNull']) !!}
            </div>
         </div>
      </div>
   <div class="form-group">
      <label>Number of members allowed in the family tree</label>
      {!! Form::text('allow_family_member', null, ['class' => 'form-control' ,'placeholder' => 'No of Family Member allow in this Plan','oninput'=>"this.value = this.value.replace(/[a-zA-Z!@#$;/-/%^&*()_+=]+/g, '')",'onpaste'=>"return false;", 'maxlength'=>4,'data-type'=>'notNull']) !!}
   </div>
   <br>
   <div class="control-group float-left">
      <div class="form-actions span6 text-center">
         <input type="button" value="Add Plan" class="btn btn-success" onclick="formValidation('new-plan','submit')">
      </div>
   </div>
   {!! Form::close() !!}

</div>

   @stop





