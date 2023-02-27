<?php $url = Request::segments(); ?>
@extends('layouts.admin')
@section('content')
<div class="card tableCard code-table">
    <div class="card-header">
        <h5>Push Notification Management</h5>
    </div>
</div>
@include('includes.flash')
<div class="card tableCard code-table">
    <div class="card-header">
        <h5>Edit Push Notification Template</h5>
        <div class="card-header-right">

        </div>
    </div>
    <?php if(isset($notification->title_identifier) || isset($notification->message_identifier)){
        echo '<div class="btn btn-warning" style="text-align:left">';
            Note: 
            if(!empty($notification->title_identifier)){
                echo '<b class="text-danger">Title Identifier: '.$notification->title_identifier.'</b><br>';
            }
            if(!empty($notification->message_identifier)){
                echo '<b class="text-danger">Message Identifier: '.$notification->message_identifier.'</b>';
            }
        echo '</div>';
    }?>
    <div class="card-body">
        {!! Form::model($notification, [
            'method' => 'PATCH',
            'id' => 'customerform',
            'files' => true ,
            'class' => 'form-horizontal',
            'url' => ['admin/notification-template/edit',base64_encode($notification->id)]
            ]) !!}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            
            <div class="control-group">
                <label for="first_name">Notification Title <span class="red">*</span></label>
                <div class="controls">
                    {!! Form::text('title', null, ['class' => 'form-control valid-text' ,'placeholder' => 'Enter Title','required']) !!}
                </div>
            </div>
            <div class="control-group">
                <label for="first_name">Content <span class="red">*</span></label>
                <div class="controls">
                    {!! Form::textarea('message', $notification->message, ['class' => 'form-control span9' ,'placeholder' => 'Enter Content','required']) !!}
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

    @stop
