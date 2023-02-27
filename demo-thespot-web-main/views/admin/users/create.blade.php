<?php $url = Request::segments(); ?>
@extends('layouts.admin')
@section('content')

<div class="card tableCard code-table">
   <div class="card-header">
      <h5>Users Management</h5>
   </div>
</div>
@include('includes.flash')
<div class="card tableCard code-table">
   <div class="card-header">
      <h5>Add New User</h5>
   </div>
   <div class="card-body">
      {!! Form::open(array('route'=>'saveNewUser','id' => 'faqAddFrom','files'=>'true','class' => 'form-horizontal','id'=>'newUser','data-validation'=>'validation','autocomplate'=>'off')) !!}
      <div class="row">
         <div class="form-group col-md-4">
            <label>Name</label>
            {!! Form::text('name',old('name'),['class'=>'form-control','placeholder'=>"Enter First Name",'data-type'=>'full_name']) !!}
         </div>
         
         <div class="form-group col-md-4">
            <label>Email</label>
            {!! Form::email('email',old('email'),['class'=>'form-control','data-type'=>'email','placeholder'=>"Email Id"]) !!}
         </div>
         <div class="form-group col-md-4">
            <label>Mobile Number</label>
            {!! Form::text('mobile',old('mobile'),['class'=>'form-control','data-type'=>'mobile','placeholder'=>"User Mobile Number",'maxlength'=>"15"]) !!}
         </div>
         <div class="form-group col-md-2">
            <label>Gender</label>
            {!! Form::select('gender',['Male'=>'Male','Female'=>'Female'],old('gender'),['class'=>'form-control','data-type'=>'notNull','placeholder'=>"Select user gender"]) !!}
         </div>
         <div class="form-group col-md-2">
            <label>User Intrested In</label>
            {!! Form::select('intrested_in',['Male'=>'Male','Female'=>'Female','Both'=>'Both'],old('intrested_in'),['class'=>'form-control','data-type'=>'atleastCharacter','placeholder'=>"Select your intrest"]) !!}
         </div>
          <div class="form-group col-md-2">
            <label>Nationality</label>
            {!! Form::text('nationality',old('nationality'),['class'=>'form-control','placeholder'=>"Enter Nationality",'data-type'=>'nationality']) !!}
         </div>
         <div class="form-group col-md-2">
            <label>Height (cm)</label>
            {!! Form::text('height',old('height'),['class'=>'form-control','data-type'=>'notNull','placeholder'=>"User Height",'maxlength'=>"3"]) !!}
         </div>
         <div class="form-group col-md-4">
            <label>Date of Birth</label>
            <input type="date" name="dob" value="" class="form-control" placeholder="User Date of Birth (dd-mm-YYYY)" max="{{date('Y-m-d',strtotime('-18 year'))}}" />
            <!-- {!! Form::text('dob',old('dob'),['class'=>'form-control dob','placeholder'=>"User Date of Birth (dd-mm-YYYY)",'onkeyup'=>"this.value = ''",'required'=>"required",'data-type'=>"dob",'onfocus'=>"(this.type='date')",'onblur'=>"(this.type='text')"]) !!} -->
         </div>
         <div class="form-group col-md-2 location_div">
            <label>Latitude</label>
            <input type="text" class="form-control" placeholder="Latitude" name="lat" id="lat" value="{{old('lat')}}" data-type="notNull">
         </div>
         <div class="form-group" style="padding:0px 15px">
            <label>Longitude</label>
            <input type="text" class="form-control" placeholder="Longitude" name="lng" id="lng" value="{{old('lng')}}" data-type="notNull">
         </div>
         <div class="form-group col-md-4">
            <label>Register as</label>
            {!! Form::select('role',['users'=>'Main User','fake_user'=>'Fake User'],old('role'),['class'=>'form-control','data-type'=>'notNull','placeholder'=>"Select Register as "]) !!}
         </div>
         <div class="form-group col-md-4">
            <label>User Images</label>
            <input type="file" name="images[]" class="form-control" multiple size="6">
         </div>
         <div class=" form-group col-md-12">
            <label>Describe about user</label>
            {!! Form::textarea('bio',old('bio'),['class'=>'form-control','placeholder'=>"Describe about user",'rows'=>3,'maxlength'=>'5000']) !!}
         </div>
      </div>
      <div class="form-group">
         <div class="form-actions text-center float-left">
            <input type="button" value="Submit" class="btn btn-theme" onclick="formValidation('newUser','submit')">
         </div>
      </div>
      {!! Form::close() !!}
      </form>
   </div>
</div>
@stop
@section('customJs')
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBGZqwQhd9aG4LQ-BOj_2ycqxiglrEwsWE&libraries=places&callback=initialize" async defer></script>
<script>
   $(document).ready(function() {
      setTimeout(function() {
         initialize();
      }, 2000);
   })

   function initialize() {
      var input = document.getElementById('location');
      var autocomplete = new google.maps.places.Autocomplete(input);
      autocomplete.addListener('place_changed', function() {
         var place = autocomplete.getPlace();
         console.log(place);
         if (!place.geometry) {
            document.getElementById('postaddressesname').value = "";
            return;
         }
         //.getElementById('postaddressesname').value = place.formatted_address;
         document.getElementById('lat').value = place.geometry['location'].lat();
         document.getElementById('lng').value = place.geometry['location'].lng();
         $('.autoPopulateAddress').trigger('input');
      });
   }
   $('#location').keydown(function() {
      document.getElementById('lat').value = '';
      document.getElementById('lng').value = '';
   });
</script>
@stop