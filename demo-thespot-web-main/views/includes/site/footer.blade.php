

<footer>
   <div class="footer-main">
      <div class="container">
         <div class="card-deck f-border-bottom">
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-7">
               <div class="foot-logo">
                  <a href="{{url('/')}}">
                  <img src="{{ config('app.asset_url')}}/assets/img/footer-logo.png" />
                  </a>
                  <!-- <p>
                     Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                  </p> -->
                  <div class="social-icon">
                     <a href="javascript:;"
                        ><i class="fab fa-facebook-f"></i
                        ></a>
                     <a href="javascript:;" class="active"
                        ><i class="fab fa-twitter"></i
                        ></a>
                     <a href="javascript:;"
                        ><i class="fab fa-linkedin-in"></i
                        ></a>
                     <a href="javascript:;"
                        ><i class="fab fa-instagram"></i
                        ></a>
                  </div>
               </div>
            </div>
            <div class="col-md-12">
               <div class="row">
                  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                     <h4 class="foot-link">Product</h4>
                     <ul class="foot-menu">
                        <li><a href="{{url('/')}}">Home</a></li>
                        <li><a href="{{url('/')}}/about-us">About us</a></li>
                        <li><a href="{{url('/')}}/how-it-works">How it works</a></li>
                        <li><a href="{{url('/')}}/contact-us">Contact us</a></li>
                     </ul>
                  </div>
                  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                     <h4 class="foot-link">Resources</h4>
                     <ul class="foot-menu">
                        <li><a href="{{url('/')}}/privacy-policy">Privacy policy</a></li>
                        <li><a href="{{ url('/')}}/faq">Support and FAQ</a></li>
                     </ul>
                  </div>
                  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                     <h4 class="foot-link">Company</h4>
                     <div class="company-info" style="color: white;">

                    <?php $address= DB::table('home-setting')->where('id',1)->pluck('footer_address')->first();
                   
                    ?>
                    {!! $address !!}
                  
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="footer-bottom">
      <div class="container">
         <div class="card-deck">
            <div class="col-xl-6">
               <p class="copyright-status">Copyright © 2020-2025 Bagoo. All rights reserved.</p>
            </div>
            <div class="col-xl-6">
               <ul class="foot-menu">
                  <li><a href="{{url('/')}}/privacy-policy">Privacy</a></li>
                  <li><a href="{{ url('/')}}/terms">terms</a></li>
                  <li><a href="#">sitmap</a></li>
               </ul>
            </div>
         </div>
      </div>
   </div>
</footer>
</div>


<script type="text/javascript" src="{{ config('app.asset_url')}}/assets/js/popper.min.js"></script> 
<script type="text/javascript" src="{{ config('app.asset_url')}}/assets/js/bootstrap.min.js"></script>  
<script type="text/javascript" src="{{ config('app.asset_url')}}/assets/js/owl.carousel.min.js"></script>
<script type="text/javascript" src="{{ config('app.asset_url')}}/assets/js/aos.js"></script>
<script type="text/javascript" src="{{ config('app.asset_url')}}/assets/js/script.js"></script>

<script src="https://www.gstatic.com/firebasejs/3.6.10/firebase.js"></script>
<script type="text/javascript" src="{{ config('app.asset_url')}}/assets/js/fcm.js"></script>



<script type="text/javascript">
$(window).scroll(function(){
if($(this).scrollTop() > 50){
    $('body').addClass('fixed');
}
else {
    $('body').removeClass('fixed');}
});
</script>

