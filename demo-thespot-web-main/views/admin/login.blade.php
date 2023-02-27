@extends('layouts.beforeLoginAdmin')
@section('content')

<div class="card">
    <div class="card-body text-center">
        <div class="mb-4">
            <img src="{{ asset('images/logo.png') }}" width="300">
        </div>
        @include('includes.flash')
        <form id="loginform" action="{{url('admin/login')}}" method="POST" data-validation="validate">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="input-group mb-3">
                <input type="email" class="form-control" placeholder="Email" name="email" required="" @if(isset($_COOKIE['email']))) value="{{$_COOKIE['email']}}" @endif>
            </div>
            <div class="input-group mb-4">
                <input type="password" class="form-control" name="password" placeholder="Password" required minlength="5" @if(isset($_COOKIE['password']))) value="{{$_COOKIE['password']}}" @endif>
            </div>
            <div class="form-group text-left">
                <div class="checkbox checkbox-fill d-inline">
                    <input type="checkbox" name="remember" id="checkbox-fill-a1" @if(isset($_COOKIE['email']))) checked @endif>
                    <label for="checkbox-fill-a1" class="cr"> Remember Me</label>
                </div>
            </div>
            <button type="submit" class="btn btn-theme shadow-2 mb-4">Login</button>
        </form>
        <p class="mb-2 text-muted"><a href="{{url('admin/forget_password')}}"><i class="fa fa-lock m-r-5"></i> Forgot password ?</a></p>
    </div>
</div>
@stop
@section('customJs')
<script>
    if ($("#loginform").length > 0) {
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
        $("#loginform").validate({


            rules: {

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