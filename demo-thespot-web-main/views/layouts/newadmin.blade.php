<!DOCTYPE html>
<html lang="en">

<head>
    <base href="http://localhost/bagoo/">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{config('app.name')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">
    <meta name="msapplication-tap-highlight" content="no">
    <link href="{{ asset('fonts/fontawesome/css/fontawesome-all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/animation/css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>


<body>
    <!--  <div class="preload"><img src="{{ asset('loading.gif') }}">
        </div> -->
    <div class="content">
        <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
            <div class="app-header header-shadow">
                @include('admin.header')
            </div>

            <div class="app-main">
                <div class="app-sidebar sidebar-shadow bg-premium-dark sidebar-text-light" style="overflow-y: auto;">
                    @include('admin.sidebar')
                </div>
                <div class="app-main__outer">
                    @yield('content')
                    @include('includes.footer')
                </div>

            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function() {
            $(".preload").fadeOut(1000, function() {
                $(".content").fadeIn(1000);
            });
        });
    </script>
    <script type="text/javascript" src="{{ asset('assetsfront/scripts/main.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>




</body>

</html>