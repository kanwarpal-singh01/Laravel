@extends('layouts.admin')
<?php $par = request()->get('par');?>
@section('content')
<div class="card tableCard code-table">
   <div class="card-header">
      <h5>Profile Management</h5>
      <div class="card-header-right">
      </div>
   </div>
   <div class="card-block pb-0">
   </div>
</div>
@include('includes.flash')
<div class="col-xl-12 col-md-12 m-b-30">
   <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item">
         <a class="nav-link <?= ($par == '' || $par == 'profile')?'active':''?>" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="profile" aria-selected="false">Profile</a>
      </li>
      <li class="nav-item complete">
         <a class="nav-link show <?= ($par == 'changepassword')?'active':''?>" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="changepassword" aria-selected="false">Change Password</a>
      </li>
   </ul>
   <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade <?= ($par == '' || $par == 'profile')?'active show':''?> " id="home" role="tabpanel" aria-labelledby="home-tab">
         <div class="col-md-6">
            <form class="form-horizontal" method="post" action="{{url('admin/updateuser')}}" enctype="multipart/form-data" name="userProfileEdit" id="customerform" data-validation="validate">
               <input type="hidden" name="_token" value="{{ csrf_token() }}">
               @if($user['profile_image'])
               <div class="control-group">
                  <div class="controls">
                     <img class="img-circle" style="width: 100px;" src="{{ url('/')}}/public/uploads/user/{{$user['profile_image']}}" />
                  </div>
               </div>
               @endif
               <div class="form-group">
                  <label>Name</label>
                  <input type="text" placeholder="Enter First Name" name="name" value="{{$user['name']}}" class="form-control" required="required" data-type="full_name"/>
               </div>
               <div class="form-group">
                  <label>Email</label>
                  <input type="email"  placeholder="Enter Email" name="email" value="{{$user['email']}}" readonly class="form-control">
               </div>
               <div class="form-group">
                  <label class="control-label">Profile Image</label>
                  <input  type="file" name="profile_image" placeholder="Image" class="form-control profile">
               </div>
               <div class="form-group">
                  <input type="button" value="Update" class="btn btn-success" onclick="formValidation('customerform','submit')">
               </div>
            </form>
         </div>
      </div>
      <div class="tab-pane fade <?= ($par == 'changepassword')?'active show':''?>" id="profile" role="tabpanel" aria-labelledby="profile-tab">
         <div class="col-md-6">
            <form class="form-horizontal" method="post" action="{{url('admin/changepassword')}}" name="userProfilePasswordEdit" id="passwordform" data-validation="validate">
               <input type="hidden" name="_token" value="{{ csrf_token() }}">
               <div class="form-group">
                  <label class="control-label">Old Password</label>
                  <input type="password" name="old_password" id="old_password" class="form-control" placeholder=" Enter Old Password" data-type="notNull" />
               </div>
               <div class="form-group">
                  <label class="control-label">New  Password</label>
                  <input type="password" name="new_password" id="password" class="form-control" placeholder=" Enter New Password" data-type="password"/>
               </div>
               <div class="form-group">
                  <label class="control-label">Confirm Password</label>
                  <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder=" Enter Confirm Password" data-type="confirm_password"/>
               </div>
               <div class="form-group">
                  <input type="button" value="Update" class="btn btn-success" onclick="formValidation('passwordform','submit')">
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
@stop
@section('customJs')
<script>
   $('#myTab li').click(function(){
      tabKey = $(this).find('a').attr('aria-controls');
      url = window.location.href;
      urlOld = url.split('?')[0];
      url = urlOld+'?par='+tabKey;
      window.history.pushState({path:url},'',url);
   });
   $('.profile').change(function(e){
        var files = e.target.files
        filesL = files[0]
        console.log(files);
        console.log(filesL);
        if(filesL.size > '3000000'){
            alert('File size less than 3 MB');
            this.value = '';
            return false;
        }

      var ext = this.value.split('.');
      ext = ext[ext.length-1];
      switch (ext) {
         case 'jpg':
         case 'jpeg':
         case 'png':
         case 'JPG':
         case 'JPEG':
         case 'PNG':
         $('#uploadButton').attr('disabled', false);
         break;
         default:
         alert('Allow image format only eg: jpg,jpeg,png.');
         this.value = '';
      }
   });
   if ($("#customerform").length > 0) {
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
      $("#customerform").validate({


         rules: {
            first_name: {
               required: true,
               maxlength: 55,
               regex: /^\S+$/
            },

            last_name: {
               required: true,
               maxlength: 55,
               regex: /^\S+$/
            },

            email: {
               required: true,
               maxlength: 50,
               email: true,
            },
            password: {
               required: true,
            }

         },
         messages: {

            first_name: {
               required: "Please enter first name",
               maxlength: "First name must less than 55 characters only.",
               regex: "First name should not have spaces."
            },
            last_name: {
               required: "Please enter last name",
               maxlength: "Last name must less than 55 characters only.",
               regex: "Last name should not have spaces."
            },
            email: {
               required: "Please enter valid email",
               email: "Please enter valid email",
               maxlength: "The email name should less than or equal to 50 characters",
            },
            password: {
               required: "Please enter password",

            }

         },
      })
   }
</script>
@stop
