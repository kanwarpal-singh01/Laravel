<?php $url = Request::segments(); ?>
@extends('layouts.admin')
@section('content')

<div class="card tableCard code-table">
   <div class="card-header">
      <h5>Subscription Management</h5>
      <div class="card-header-right">
         <a href="{{ route('subscriptions') }}" class="btn btn-primary"><i class="fa fa-list"> Subscription List</i></a>
      </div>
   </div>
</div>
@include('includes.flash')
<div class="card tableCard code-table">
   <div class="card-header">
      <h5>Edit Subscription</h5>
   </div>
   <div class="card-body">
      {!! Form::open(array('id' => 'new-plan','class' => 'form-horizontal','autocomplete'=>"off",'data-validation'=>'validate')) !!}
      <div class="form-group">
         <label>Plan Name </label>
         {!! Form::text('plan_name', $plan->plan_name, ['class' => 'form-control' ,'placeholder' => 'Enter Plan Title','data-type'=>'plan_name']) !!}
      </div>
      <div class="form-group">
         <label>Description </label>
         {!! Form::textarea('description', $plan->description, ['class' => 'form-control' ,'placeholder' => 'Short Description about Plan','rows'=>3,'maxlength'=>350,'data-type'=>'notNull']) !!}
      </div>
      <div class="form-group">
         <label>Price </label>
         {!! Form::text('amount', $plan->amount, ['class' => 'form-control' ,'placeholder' => 'Subscription Plan Price','data-type'=>'price','maxlength'=>6]) !!}
      </div>
      <div class="form-group">
         <label>Discount </label>
         {!! Form::text('discount', $plan->discount, ['class' => 'form-control' ,'placeholder' => 'Discount','oninput'=>"this.value = this.value.replace(/[a-zA-Z!@#/-/$%;^&*.()_+=]+/g, '')",'onpaste'=>"return false;", 'maxlength'=>2,'data-type'=>'notNull']) !!}
      </div>
      <div class="form-group">
         <label>Discount Text</label>
         {!! Form::text('discount_text', $plan->discount_text, ['class' => 'form-control' ,'placeholder' => 'Discount Text']) !!}
      </div>
      <div class="form-group">
         <label>Features</label>
         {!! Form::textarea('feature',$plan->feature, ['class' => 'form-control' ,'placeholder' => 'Enter features','data-type'=>'notNull']) !!}
      </div>
      <div class="form-group">
         <label>Drop Pin</label>
         {!! Form::text('drop_pin',$plan->drop_pin, ['class' => 'form-control' ,'placeholder' => 'Drop Pin Description','data-type'=>'notNull']) !!}
      </div>
      <div class="form-group">
         <label>Unlimited Chat</label>
         {!! Form::text('unlimited_chat',$plan->unlimited_chat, ['class' => 'form-control' ,'placeholder' => 'Unlimited chat description','data-type'=>'notNull']) !!}
      </div>
      <div class="form-group">
         <label>Hide Ads</label>
         {!! Form::text('hide_ads',$plan->hide_ads, ['class' => 'form-control' ,'placeholder' => 'Hide Ads','data-type'=>'notNull']) !!}
      </div>
      <div class="form-group">
         <label>Spy Mode</label>
         {!! Form::text('spy_mode',$plan->spy_mode, ['class' => 'form-control' ,'placeholder' => 'SPY mode description','data-type'=>'notNull']) !!}
      </div>
      <br>
      <div class="control-group float-left">
         <div class="form-actions span6 text-center">
            <input type="button" value="Update Plan" class="btn btn-success" onclick="formValidation('new-plan','submit')">
         </div>
      </div>
      {!! Form::close() !!}

   </div>
   @stop