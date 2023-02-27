<script type="text/javascript" src="{{asset('front/js/popper.min.js')}}"></script>  
<script type="text/javascript" src="{{asset('front/js/bootstrap.min.js')}}"></script>   
<script type="text/javascript" src="{{asset('front/js/mdb.min.js')}}"></script>
<script type="text/javascript" src="{{asset('front/js/owl.carousel.min.js')}}"></script>
<script type="text/javascript" src="{{asset('front/js/jquery.validate.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.uploadfile.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.timepicker.min.js')}}"></script>
<script src="https://apis.google.com/js/platform.js?onload=init"></script>
<script type="text/javascript" src="{{asset('front/js/fb_logout.js')}}"></script> 
<script>
   $(window).scroll(function()
   {
      
       var body = $('body'),
         scroll = $(window).scrollTop();
         
       if (scroll >= 5) body.addClass('fixed');
       else body.removeClass('fixed');
   });
   
   
   $('.tile')
   // tile mouse actions
   .on('mouseover', function(){
     $(this).children('.photo').css({'transform': 'scale('+ $(this).attr('data-scale') +')'});
   })
   .on('mouseout', function(){
     $(this).children('.photo').css({'transform': 'scale(1)'});
   })
   .on('mousemove', function(e){
     $(this).children('.photo').css({'transform-origin': ((e.pageX - $(this).offset().left) / $(this).width()) * 100 + '% ' + ((e.pageY - $(this).offset().top) / $(this).height()) * 100 +'%'});
   })
   // tiles set up
   .each(function(){
     $(this)
       // add a photo container
       .append('<div class="photo"></div>')
       // some text just to show zoom level on current item in this example
       .append('<div class="txt"><div class="x">'+ $(this).attr('data-scale') +'x</div>ZOOM ON<br>HOVER</div>')
       // set up a background image for each tile based on data-image attribute
       .children('.photo').css({'background-image': 'url('+ $(this).attr('data-image') +')'});
   })
   
   
   wow = new WOW({
       boxClass: 'wow', // default
       animateClass: 'animated', // default
       offset: 0, // default
       mobile: true, // default
       live: true // default
   })
   wow.init();
   
   $('.home_slider').owlCarousel({
       loop:true,
       autoplay:false,
       nav:true,
       items:1,
       animateIn: 'fadeIn',
       animateOut: 'fadeOut',
   })
   
   $('.front_slider').owlCarousel({
       loop:true,
       autoplay:true,
       nav:true,
       items:1,
       animateIn: 'fadeIn',
       animateOut: 'fadeOut',    
       autoplayHoverPause:true
   })
   
   $('.categories').owlCarousel({
       loop:true,
       margin:30,
       autoplay:true,
       nav:true,
       responsive:{
               0:{
                   items:1
               },
               600:{
                   items:3
               },
               1000:{
                   items:5
               }
           }
   })
   
   $('.product_slider').owlCarousel({
       loop:false,
       margin:30,
       autoplay:true,
       nav:true,
       responsive:{
               0:{
                   items:1
               },
               600:{
                   items:3
               },
               1000:{
                   items:4
               }
           }
   })
   
   $('.feature_ads').owlCarousel({
       loop:true,
       margin:30,
       autoplay:true,
       nav:true,
       responsive:{
               0:{
                   items:1
               },
               600:{
                   items:3
               },
               1000:{
                   items:4
               }
           }
   })
   
      // card 
      var perspectiveHover = function() {
   
       var $card               = $('.donation_ger'),
           $cardInner          = $('.pre-shape1'),
           $cardBorder         = $('.RaseelCardName'),
           $cardText           = $('.pre-shape2'),
           xAngle              = 0,
           yAngle              = 0,
           zValue              = 0,
           xSensitivity        = 15,
           ySensitivity        = 25,
           borderSensitivity   = 0.66,
           textSensitivity     = 0.45,
           restAnimSpeed       = 300,
           perspective         = 500;
     
       $card.on('mousemove', function(element) {
           var $item = $(this),
   
               // Get cursor coordinates
               XRel = element.pageX - $item.offset().left,
               YRel = element.pageY - $item.offset().top,
               width = $item.width();
   
           // Build angle val from container width and cursor value
           xAngle = (0.5 - (YRel / width)) * xSensitivity;
           yAngle = -(0.5 - (XRel / width)) * ySensitivity;
   
           // Container isn't manipulated, only child elements within
           updateElement($item.children($cardInner));
       });
     
       // Move element around
       function updateElement(modifyLayer) 
       {
           modifyLayer.css({
               'transform': 'perspective('+ perspective + 'px) translateZ(' + zValue + 'px) rotateX(' + xAngle + 'deg) rotateY(' + yAngle + 'deg)',
               'transition': 'none'
           });
   
           modifyLayer.find($cardBorder).css({
               'transform': 'perspective(' + perspective + 'px) translateZ(' + zValue + 'px) rotateX(' + (xAngle/borderSensitivity) + 'deg) rotateY(' + (yAngle/borderSensitivity) + 'deg)',
               'transition': 'none'
           });
         
           modifyLayer.find($cardText).css({
               'transform': 'perspective(' + perspective + 'px) translateZ(' + zValue + 'px) rotateX(' + (xAngle/textSensitivity) + 'deg) rotateY(' + (yAngle/textSensitivity) + 'deg) translateY('+ xAngle +'px)',
               'transition': 'text-shadow'
           });
       }
   
       // Reset element to default state
       $card.on('mouseleave', function() {
   
           modifyLayer = $(this).children($cardInner);
   
           modifyLayer.css({
               'transform': 'perspective(' + perspective + 'px) translateZ(0) rotateX(0) rotateY(0)',
               'transition': 'transform ' + restAnimSpeed + 'ms linear'
           });
   
           modifyLayer.find($cardBorder).css({
               'transform': 'perspective(' + perspective + 'px) translateZ(0) rotateX( 0deg) rotateY(0)',
               'transition': 'transform ' + restAnimSpeed + 'ms linear'
           });
         
            modifyLayer.find($cardText).css({
               'transform': 'perspective(' + perspective + 'px ) translateZ(0) rotateX(0) rotateY(0) translateX(0)',
               'transition': 'all ' + restAnimSpeed + 'ms linear'
           });
       });
   };
   
   perspectiveHover();
   var slider = $(".carousel-full");
   var thumbnailSlider = $(".carousel-thumbs");
   var duration = 500;
   var syncedSecondary = true;
   
   setTimeout(function() 
   {
       $(".cloned .item-slider-model a").attr("data-fancybox", "group-2");
   }, 500);
   
   // carousel function for main slider
   slider
       .owlCarousel({
           loop: true,
           nav: true,
           navText: ["", ""],
           items: 1,
           lazyLoad: true,
           autoplay: true,
           smartSpeed: 600,
           autoplayHoverPause: true
       }).on("changed.owl.carousel", syncPosition);
   
   // carousel function for thumbnail slider
   thumbnailSlider.on("initialized.owl.carousel", function() {
           thumbnailSlider
               .find(".owl-item")
               .eq(0)
               .addClass("current");
       }).owlCarousel({
           loop: false,
           nav: false,
           margin: 10,
           smartSpeed: 600,
           responsive: {
               0: {
                   items: 4
               },
               600: {
                   items: 4
               },
               1200: {
                   items: 4,
                   margin: 20
               }
           }
       }).on("changed.owl.carousel", syncPosition2);
   
   // on click thumbnaisl
   thumbnailSlider.on("click", ".owl-item", function(e) 
   {
       e.preventDefault();
       var number = $(this).index();
       slider.data("owl.carousel").to(number, 300, true);
   });
   
   function syncPosition(el) 
   {
       var count = el.item.count - 1;
       var current = Math.round(el.item.index - el.item.count / 2 - 0.5);
   
       if (current < 0) {
           current = count;
       }
       if (current > count) {
           current = 0;
       }
   
       thumbnailSlider
           .find(".owl-item")
           .removeClass("current")
           .eq(current)
           .addClass("current");
       var onscreen = thumbnailSlider.find(".owl-item.active").length - 1;
       var start = thumbnailSlider
           .find(".owl-item.active")
           .first()
           .index();
       var end = thumbnailSlider
           .find(".owl-item.active")
           .last()
           .index();
   
       if (current > end) {
           thumbnailSlider.data("owl.carousel").to(current, 100, true);
       }
       if (current < start) {
           thumbnailSlider.data("owl.carousel").to(current - onscreen, 100, true);
       }
   }
   
   function syncPosition2(el) 
   {
       if (syncedSecondary) {
           var number = el.item.index;
           slider.data("owl.carousel").to(number, 100, true);
       }
   }
   
   $(document).ready(function()
   {
     
     /* 1. Visualizing things on Hover - See next part for action on click */
       $('#stars li').on('mouseover', function()
       {
           var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
          
           // Now highlight all the stars that's not after the current hovered star
           $(this).parent().children('li.star').each(function(e){
             if (e < onStar) {
               $(this).addClass('hover');
             }
             else {
               $(this).removeClass('hover');
             }
           });
       
       }).on('mouseout', function()
       {
           $(this).parent().children('li.star').each(function(e){
             $(this).removeClass('hover');
           });
       });
     
     
     /* 2. Action to perform on click */
       $('#stars li').on('click', function()
       {
           var onStar = parseInt($(this).data('value'), 10); // The star currently selected
           var stars = $(this).parent().children('li.star');
           
           for (i = 0; i < stars.length; i++) {
             $(stars[i]).removeClass('selected');
           }
           
           for (i = 0; i < onStar; i++) {
             $(stars[i]).addClass('selected');
           }
           
           // JUST RESPONSE (Not needed)
           var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
           var msg = "";
           if (ratingValue > 1) {
               msg = "Thanks! You rated this " + ratingValue + " stars.";
           }
           else {
               msg = "We will improve ourselves. You rated this " + ratingValue + " stars.";
           }
           responseMessage(msg);
           
       });
   });
   
   
   function responseMessage(msg) 
   {
     $('.success-box').fadeIn(200);  
     $('.success-box div.text-message').html("<span>" + msg + "</span>");
   }
</script>