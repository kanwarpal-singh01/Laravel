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
<div class="row">
   <div class="col-md-8">
      <div class="card tableCard code-table">
         <div class="card-header">
            <h5>Subscription Detail</h5>
         </div>
         <div class="card-body box-profile">
            <ul class="list-group list-group-unbordered mb-3">
               <li class="list-group-item">
                  <b>Plan Name</b> <a class="float-right">{{$subscriptionDetail->plan_name}}</a>
               </li>
               <li class="list-group-item">
                  <b>Description</b> <a class="float-right">{{$subscriptionDetail->description}}</a>
               </li>
               <li class="list-group-item">
                  <b>Discount</b> <a class="float-right">${{$subscriptionDetail->discount}}</a>
               </li>
               <li class="list-group-item">
                  <b>Discount Text </b> <a class="float-right">{{$subscriptionDetail->discount_text}}</a>
               </li>
               <li class="list-group-item">
                  <b>Price</b> <a class="float-right">${{$subscriptionDetail->amount}}</a>
               </li>
               <li class="list-group-item">
                  <b>Features</b> <a class="float-right">{{$subscriptionDetail->feature}}</a>
               </li>
               <li class="list-group-item">
                  <b>Drop Pin</b> <a class="float-right">{{$subscriptionDetail->drop_pin}}</a>
               </li>
               <li class="list-group-item">
                  <b>Unlimited Chat</b> <a class="float-right">{{$subscriptionDetail->unlimited_chat}}</a>
               </li>
               <li class="list-group-item">
                  <b>Hide Ads</b> <a class="float-right">{{$subscriptionDetail->hide_ads}}</a>
               </li>
               <li class="list-group-item">
                  <b>Spy Mode</b> <a class="float-right">{{$subscriptionDetail->spy_mode}}</a>
               </li>
               <li class="list-group-item">
                  <b>Status</b> <a class="float-right">{{($subscriptionDetail->status == '1')?'Active':'Inactive'}}</a>
               </li>
            </ul>

         </div>
      </div>
   </div>
</div>

<style>
   /* .widget-title, .modal-header, .table th, div.dataTables_wrapper .ui-widget-header{
      background:#2e363f;
   }
   .table-icon span { color:#fff; }
   .table-icon h5 { color:#fff; }*/
   table,
   th,
   td {
      border: 1px black;
   }

   .table-user-information tr td:first-child {
      font-weight: bold;
      width: 150px;
      position: relitive;
   }

   .table-user-information tr td {
      border-bottom: 1px solid #FFF;
      padding: 10px 0px;
      ;
   }
</style>
@stop