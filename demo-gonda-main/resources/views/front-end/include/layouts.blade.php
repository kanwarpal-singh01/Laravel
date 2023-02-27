<html>
@include('front-end.include.head')
<body>
<div class="wraper">
    @include('front-end.include.header')
    @jquery
    @toastr_js
    @toastr_render
    @yield('content')
    @include('front-end.include.footer')
</div>
</body>

<script type="text/javascript" src="{{asset('public/front-end/js/jquery-3.3.1.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/front-end/js/popper.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/front-end/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/front-end/js/mdb.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/front-end/js/owl.carousel.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

@yield('scripts')
<!-- Start of HubSpot Embed Code -->
<script type="text/javascript" id="hs-script-loader" async defer src="//js-na1.hs-scripts.com/20039590.js"></script>
<!-- End of HubSpot Embed Code -->
<script type="text/javascript">
    $('.people-say').owlCarousel({
        loop:false,
        margin:30,
        nav:true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2
            },
            1000:{
                items:3
            }
        }
    })

    $('.inside-app').owlCarousel({
        stagePadding:350,
        loop:false,
        margin:55,
        nav:true,
        autoplay:true,
        smartSpeed: 1000,
        autoplayTimeout:4000,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2
            },
            1000:{
                items:3
            }
        }
    })

    $(function() {
        $('.scroll-down').click (function() {
            $('html, body').animate({scrollTop: $('section.ok-about').offset().top }, 'slow');
            $('html, body').addClass('kuldeep');
            return false;
        });
    });

    $(function() {
        $('.scroll-top').click (function() {
            $('html, body').animate({scrollTop: $('body').offset().top }, 'slow');
            $('html, body').removeClass('kuldeep');
            return false;
        });
    });


    $('#myModal').on('shown.bs.modal', function () {
        $('#video1')[0].play();
    })
    $('#myModal').on('hidden.bs.modal', function () {
        $('#video1')[0].pause();
    })

</script>

<script type="text/javascript">
    $(window).scroll(function(){var body=$('body'),scroll=$(window).scrollTop();if(scroll>=5){body.addClass('fixed');}else{body.removeClass('fixed');}});
</script>

</html>