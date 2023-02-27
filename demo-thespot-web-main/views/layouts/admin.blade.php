<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<?php //pr(asset('/'));die;
?>

<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <!-- CSRF Token -->
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}" />
   <title>Admin- The Spot</title>
   <link href="{{ asset('fonts/fontawesome/css/fontawesome-all.min.css') }}" rel="stylesheet">
   <link href="{{ asset('plugins/animation/css/animate.min.css') }}" rel="stylesheet">
   <link href="{{ asset('css/style.css') }}" rel="stylesheet">
   <link href="{{ asset('css/developer.css') }}" rel="stylesheet">
</head>

<body>
   <div class="overlay">
      <div class="overlay__inner">
         <div class="overlay__content"><span class="spinner"></span></div>
      </div>
   </div>
   <?php $pageHeader = $breadcrumb = ''; ?>
   @include('admin.header')
   @include('admin.sidebar')
   <div class="pcoded-main-container">
      <div class="pcoded-wrapper">
         <div class="pcoded-content">
            <div class="pcoded-inner-content">
               <div class="page-header">
                  <div class="page-block">
                     <div class="row align-items-center">
                        <div class="col-md-12">
                           <div class="page-header-title">
                              <h5 class="m-b-10">{{$pageHeader}}</h5>
                           </div>
                           <ul class="breadcrumb">
                              {{$breadcrumb}}
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="main-body">
                  <div class="page-wrapper">
                     @yield('content')
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <footer>

   </footer>
   <!-- Scripts -->
   <script src="{{ asset('js/vendor-all.min.js') }}"></script>
   <script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
   <script src="{{ asset('js/pcoded.min.js') }}"></script>
   <script src="{{ asset('js/developer.js') }}"></script>
   @yield('customJs')
   <script type="text/javascript">
      $(document).ready(function() {
         setTimeout(function() {
            $('.overlay').fadeOut();
         }, 700)
      })
   </script>
   <script type="text/javascript">
    
    $('#notification_click').click(function(){
    $.ajax({
                    url: '{{url('/')}}/readChat',
                    type: 'GET',
                   
                    dataType: 'JSON',
                    /* remind that 'data' is the response of the AjaxController */
                    success: function (data) { 
                     console.log("Fds");
                        //$(".writeinfo").append(data.msg); 
                    }
                }); 
});
</script>
</body>

</html>