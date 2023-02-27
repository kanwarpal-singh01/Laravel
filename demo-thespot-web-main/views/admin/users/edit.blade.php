<?php $url = Request::segments(); ?>
@extends('layouts.admin')
@section('content')

<div class="card tableCard code-table">
    <div class="card-header">
        <h5>Users Management</h5>
        <div class="card-header-right">
            <a href="{{ route('userList') }}" class="btn btn-primary"><i class="fa fa-list"> Users List</i></a>
        </div>
    </div>
</div>
@include('includes.flash')
<div class="card tableCard code-table">
    <div class="card-header">
        <h5>Edit</h5>
    </div>
    <div class="card-body">
        {!! Form::model($record, ['method' => 'PATCH','id' => 'userform','files' => true ,'data-validation'=>'validation','class' => 'form-horizontal','url' => ['admin/'.$url[1].'/edit',base64_encode($record->id)]]) !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" value="" id="lattitude">
        <input type="hidden" value="" id="longitude">
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
                {!! Form::text('mobile',old('mobile'),['class'=>'form-control','data-type'=>'notNull','placeholder'=>"User Mobile Number",'maxlength'=>"15"]) !!}
            </div>
            <div class="form-group col-md-2">
                <label>Gender</label>
                {!! Form::select('gender',['Male'=>'Male','Female'=>'Female'],(isset($record->userDetail->gender)?$record->userDetail->gender:''),['class'=>'form-control','data-type'=>'notNull','placeholder'=>"Select user gender"]) !!}
            </div>
            <div class="form-group col-md-2">
                <label>User Intrested In</label>
                {!! Form::select('intrested_in',['Male'=>'Male','Female'=>'Female','Both'=>'Both'],(isset($record->userDetail->intrested_in)?$record->userDetail->intrested_in:''),['class'=>'form-control','data-type'=>'atleastCharacter','placeholder'=>"Select your intrest"]) !!}
            </div>
             <div class="form-group col-md-2">
                <label>Nationality</label>
                {!! Form::text('nationality',(isset($record->userDetail->nationality)?$record->userDetail->nationality:''),['class'=>'form-control','data-type'=>'notNull','placeholder'=>"Nationality"]) !!}
            </div>
            <div class="form-group col-md-2">
                <label>Height (cm)</label>
                {!! Form::text('height',(isset($record->userDetail->height)?$record->userDetail->height:''),['class'=>'form-control','data-type'=>'notNull','placeholder'=>"User Height",'maxlength'=>"3"]) !!}
            </div>
            <div class="form-group col-md-4">
                <label>Date of Birth</label>
                <input type="date" name="dob" value="{{isset($record->userDetail->dob)?$record->userDetail->dob:''}}" class="form-control" placeholder="User Date of Birth (dd-mm-YYYY)">
                <?php /* ?><?php */ ?>
            </div>
            <!-- <div class="form-group col-md-4 location_div">
                <label>Location</label>
                {!! Form::text('location',(isset($record->userDetail->location)?$record->userDetail->location:''),['class'=>'form-control autoPopulateAddress','placeholder'=>"Enter Location",'required'=>"required",'data-type'=>"autoPopulateAddress",'id'=>'location']) !!}
                <input type="hidden" name="lat" id="lat" style="opacity: 0" value="<?= $record->lat ?>">
                <input type="hidden" name="lng" id="lng" style="opacity: 0" value="<?= $record->lng ?>">
            </div> -->
            <div class="form-group col-md-2 location_div">
                <label>Latitude</label>
                <input type="text" class="form-control" placeholder="Latitude" name="lat" id="lat" value="<?= $record->lat ?>" data-type="notNull">
            </div>
            <div class="form-group col-md-2" style="padding:0px 15px">
                <label>Longitude</label>
                <input type="text" class="form-control" placeholder="Longitude" name="lng" id="lng" value="<?= $record->lng ?>" data-type="notNull">
            </div>
            <div class="form-group col-md-4">
                <label>Register as</label>
                {!! Form::select('role',['users'=>'Main User','fake_user'=>'Fake User'],$record->role,['class'=>'form-control','data-type'=>'notNull','placeholder'=>"Select Register as "]) !!}
            </div>
            <div class="form-group col-md-4">
                <label>User Images</label>
                <span id="galeria">
              
</span>
               
                <input type="file" name="images[]" class="form-control" onchange="previewMultiple(event)" id="adicionafoto" multiple>
            </div>

            <div class="form-group col-md-12">
                <label>Describe about user</label>
                {!! Form::textarea('bio',(isset($record->userDetail->bio)?$record->userDetail->bio:''),['class'=>'form-control','placeholder'=>"Describe about user",'rows'=>3,'maxlength'=>'5000']) !!}
            </div>
          
            <?php $profileImages = explode(',', $userDetail->images); 
                 // dd($profileImages);
                 $count=0;
                 ?>
                    @if(count($profileImages) > 0 && $userDetail->images != "")
    <div class="col-md-12">
        <div class="card">
             <div class="card-header">
                <h5>Images</h5>
            </div>
            <div class="card-block p-0">
                 <ul class="list-group list-group-unbordered mb-3">
                
                    <li class="list-group-item">
                        <div class="row">
                             <!--  <a style="margin-top: 25px;" class="col-md-4"><i onclick="deleteImage();" class="fa fa-times" aria-hidden="true" style="position: absolute;right: 24px;background: black;padding: 4px;color: white;border-radius: 4px;"></i><img src="{{ config('app.profile_url')}}{{$userDetail->profile_image}}" style="border-radius: 10px;" width="200" height="200"></a><br> -->
                            @foreach($profileImages as $key => $value)
                       
                            <div style="margin-top: 25px;" class="col-md-2"><i onclick="deleteImage({{$count++}},{{$userDetail->id}});" class="fa fa-times" aria-hidden="true" style="position: absolute;right: 24px;background: black;padding: 4px;color: white;border-radius: 4px;"></i><a href="{{ config('app.profile_url')}}{{$value}}"><img src="{{ config('app.profile_url')}}{{$value}}" style="border-radius: 10px;" width="200" height="200"></a></div><br>
                      
                            @endforeach
                        </div>
                    </li>
                   
                </ul>
                <div class="border-top"></div>
            </div>
        </div>
    </div>
    
     @endif
     <div class="form-group col-md-12">

<input type="button" value="Update" class="btn btn-success" onclick="formValidation('userform','submit')">
<div class="form-actions text-center">
</div>
{!! Form::close() !!}
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
        <script>
            if ($(" #customerform").length >
                0) {
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
                        user_name: {
                            required: true,
                            maxlength: 55,
                            regex: /^[_A-z0-9]*((-|\s)*[_A-z0-9])*$/
                        },

                        email: {
                            required: true,
                            maxlength: 50,
                            email: true,
                        },


                        phone: {
                            required: true,
                            digits: true,
                            minlength: 7,
                            maxlength: 15,
                        },



                    },
                    messages: {

                        user_name: {
                            required: "Please enter User name",
                            maxlength: "User name must less than 55 characters only.",
                            regex: "Special character not allowed"
                        },

                        email: {
                            required: "Please enter valid email",
                            email: "Please enter valid email",
                            maxlength: "The email name should less than or equal to 50 characters",
                        },


                    },
                })
            }
        </script>
        <script type="text/javascript">
    function deleteImage(id="",user_id="") {
         if (confirm("Are you sure you want to delete?")){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            image_id: id,
            user_id: user_id
        },
        url: "{{url('/')}}/deleteImage",
        success: function(response) {
          window.location.reload();
        }
    });
    }
    }
    function previewMultiple(event){
        var saida = document.getElementById("adicionafoto");
        var quantos = saida.files.length;
        for(i = 0; i < quantos; i++){
            var urls = URL.createObjectURL(event.target.files[i]);
            document.getElementById("galeria").innerHTML += '<img style="margin-left: 5px;border-radius: 5px;" src="'+urls+'" width="25" height="25" >';
        }
    }
</script>
        @stop