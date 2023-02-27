@extends('layouts.beforeLoginAdmin')
@section('content')
<div class="card">

   <div class="card-body text-center">
      <div class="mb-4">
         <img src="{{ asset('images/logo.png') }}" width="300">
      </div>
      <h3 class="mb-4">Reset Password</h3>
      @include('includes.flash')
            <input type="hidden" name="role" value="admin" />
            <form method="POST" action="{{ url('admin/send_password_request') }}" class="login100-form validate-form p-b-33 p-t-5">
               {{ csrf_field() }}
               <div class="input-group mb-3">
                  <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}" required>
               </div>
               <button type="submit" class="btn btn-primary mb-4 shadow-2">Reset Password</button>
            </form>
      <p class="mb-0 text-muted">Already have an account? <a href="{{url('admin/login')}}" id="login-link" class="text-muted"><i class="fa fa-sign-in"></i>Login</a></p>
   </div>
</div>
@stop