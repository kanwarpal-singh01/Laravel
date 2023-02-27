{{--<!DOCTYPE html>--}}
{{--<html lang="en">--}}
{{--<head>--}}
{{--    <meta charset="UTF-8" />--}}
{{--    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1.0">--}}
{{--    <title>Login Page in HTML with CSS Code Demo</title>--}}
{{--    <meta name="description" content="Here is code example of CSS simple login form. See the demo and downlaod free login form template. The form have quite uniqe design with username, password and a button fields." />--}}
{{--    <meta name="author" content="Codeconvey" />--}}
{{--    <!--Only for demo purpose - no need to add.-->--}}
{{--    <link rel="stylesheet" type="text/css" href="demo.css" />--}}
{{--    <link rel="stylesheet" type="text/css" href="style.css" />--}}
{{--    <style>--}}


{{--        body{--}}
{{--            height:100vh;--}}
{{--            background: rgba(241,231,103,1);--}}
{{--            background: -moz-linear-gradient(-45deg, rgba(241,231,103,1) 0%, rgba(254,182,69,1) 100%);--}}
{{--            background: -webkit-gradient(left top, right bottom, color-stop(0%, rgba(241,231,103,1)), color-stop(100%, rgba(254,182,69,1)));--}}
{{--            background: -webkit-linear-gradient(-45deg, rgba(241,231,103,1) 0%, rgba(254,182,69,1) 100%);--}}
{{--            background: -o-linear-gradient(-45deg, rgba(241,231,103,1) 0%, rgba(254,182,69,1) 100%);--}}
{{--            background: -ms-linear-gradient(-45deg, rgba(241,231,103,1) 0%, rgba(254,182,69,1) 100%);--}}
{{--            background: linear-gradient(135deg, rgba(241,231,103,1) 0%, rgba(254,182,69,1) 100%);--}}
{{--            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f1e767', endColorstr='#feb645', GradientType=1 );--}}
{{--        }--}}
{{--        .simpleLogin {--}}

{{--        }--}}
{{--        .simpleLogin form {--}}
{{--            max-width: 400px;--}}
{{--            margin: auto;--}}
{{--            background:#fefefd;--}}

{{--            box-shadow: 0 10px 10px #222;--}}
{{--        }--}}

{{--        .simpleLogin form fieldset {--}}
{{--            border: 0 none;--}}
{{--            margin: 0;--}}
{{--            padding: 20px;--}}
{{--        }--}}
{{--        .simpleLogin form legend,--}}
{{--        .simpleLogin form fieldset input {--}}
{{--            font-family: Open Sans;--}}
{{--            font-size:15px;--}}
{{--        }--}}

{{--        .simpleLogin form legend {--}}
{{--            background-color: #8fc400;--}}
{{--            border-top: 0 none;--}}
{{--            color: white;--}}
{{--            display: table-cell;--}}
{{--            padding: 10px 20px;--}}
{{--            width: auto;--}}
{{--        }--}}
{{--        .simpleLogin form fieldset input {--}}
{{--            width: 90%;--}}
{{--            margin: 10px 0;--}}
{{--            padding: 10px 5%;--}}
{{--            border: thin #8fc400 solid;--}}
{{--        }--}}
{{--        .simpleLogin input[type="submit"] {--}}
{{--            width: 100px;--}}
{{--            float: right;--}}
{{--            background: #8fc400;--}}
{{--            color: white;--}}
{{--            transition: .2s;--}}
{{--            border: 0;--}}
{{--            cursor:pointer;--}}
{{--        }--}}


{{--        .simpleLogin input[type="submit"]:focus,--}}
{{--        .simpleLogin input[type="submit"]:hover,--}}
{{--        .simpleLogin input[type="submit"]:active {--}}
{{--            padding: 10px 5%;--}}
{{--            background:#B3E226;--}}
{{--            outline: none;--}}
{{--        }--}}

{{--    </style>--}}
{{--</head>--}}
{{--<body>--}}

{{--<div class="ScriptTop">--}}
{{--    <div class="rt-container">--}}
{{--        <div class="col-rt-4" id="float-right">--}}

{{--        </div>--}}
{{--        <div class="col-rt-2">--}}
{{--            <ul>--}}
{{--                <li><a href="https://codeconvey.com/Tutorials/fixed-background-image-scrolling-content">Previous Demo</a></li>--}}
{{--                <li><a href="https://codeconvey.com/login-page-in-html-with-css-code">Back to the Article</a></li>--}}
{{--            </ul>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

{{--<header class="ScriptHeader">--}}
{{--    <div class="rt-container">--}}
{{--        <div class="col-rt-12">--}}
{{--            <div class="rt-heading" style="text-align: center">--}}
{{--                <h1>Login Page</h1>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</header>--}}

{{--<section>--}}
{{--    <div class="rt-container">--}}
{{--        <div class="col-rt-12">--}}

{{--            <div class="simpleLogin">--}}
{{--                <form method="post" action="{{route('frontend.login')}}">--}}
{{--                    @csrf--}}
{{--                    <legend>Let's Login</legend>--}}
{{--                    <fieldset>--}}
{{--                        <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email">--}}
{{--                        @error('email')--}}
{{--                        <span class="invalid-feedback" role="alert">--}}
{{--                            <strong>{{ $message }}</strong>--}}
{{--                        </span>--}}
{{--                        @enderror--}}

{{--                        <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" placeholder="Password">--}}

{{--                        @error('password')--}}
{{--                        <span class="invalid-feedback" role="alert">--}}
{{--                            <strong>{{ $message }}</strong>--}}
{{--                        </span>--}}
{{--                        @enderror--}}
{{--                        <button id="submit" type="submit">Submit</button>--}}
{{--                    </fieldset>--}}
{{--                </form>--}}

{{--            </div>--}}

{{--        </div>--}}
{{--    </div>--}}
{{--</section>--}}



{{--</body>--}}
{{--</html>--}}
