@extends('layouts.beforeLoginAdmin')
@section('content')

<div class="card">

   <div class="card-body text-center">
      <div class="mb-4">
         
      </div>
      <h3 class="mb-4">Reset Password</h3>
      @include('includes.flash')
      <form method="POST" action="{{ url('admin/update_password') }}" class="login100-form validate-form p-b-33 p-t-5" id="reset_password" data-validation='validation'>

         {{ csrf_field() }}
         <input type="hidden" name="role" value="admin" />
         <input type="hidden"  value ="{{$id}}" name="id"/>
         <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="New Password" id="password" name="password" data-type ="password">
         </div>
         <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" value="{{ old('password_confirmation') }}"  data-type="confirm_password">
         </div>
         <button type="button" class="btn btn-primary mb-4 shadow-2" onclick="formValidation('reset_password','submit')">Reset Password</button>
      </form>
   </div>
</div>
@stop
