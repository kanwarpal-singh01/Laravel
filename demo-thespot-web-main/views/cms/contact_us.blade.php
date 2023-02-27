@extends('layouts.site')
@section('content')

<div class="wrapper">

    <div class="bannerdiv">
        <div class="container">
            <div class="card-deck">
                <div class="col-xl-12">
                    <div class="banner_content">
                        <h3>contact us</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Us Section Starts -->

    <div class="contact-us-section section-padding section-square-morphin">
        <div class="section-heading">
            <!-- <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p> -->
        </div>


    
                <div class="container">
         <div class="card-deck">
            <div class="col-xl-6 col-lg-6 col-md-6">
               <div class="contact-form">
               @include('includes.flashMessage')
                  <form action="{{ url('/') }}/save-contact" method="post" id="contact-us-form">
                  {{ csrf_field() }}
                    <div class="card-deck">
                      <div class="col-xl-6">
                        <div class="form-group">
                          <input type="text" class="form-control" placeholder="First Name" name="first_name"/>
                        </div>
                      </div>
                      <div class="col-xl-6">
                        <div class="form-group">
                          <input type="text" class="form-control" placeholder="Last Name" name="last_name"/>
                        </div>
                      </div>
                      <div class="col-xl-12">
                        <div class="form-group">
                          <input type="text" class="form-control" placeholder="Phone Number" name="phone"/>
                        </div>
                      </div>
                      <div class="col-xl-12">
                        <div class="form-group">
                          <input type="text" class="form-control" placeholder="You Email Address" name="email"/>
                        </div>
                      </div>
                      <div class="col-xl-12">
                        <div class="form-group">
                          <textarea type="text" class="form-control" placeholder="Type Your Message" name="message"></textarea>
                        </div>
                      </div>
                    </div>
                     <button type="submit" class="btn know-more-btn">Submit</button>
                  </form>
               </div>
            </div>
            <div class="col-xl-6">
                    <div class="map-section">

                   <?php  $map= DB::table('home-setting')->where('id',1)->pluck('contact_map')->first(); 
                   
                   echo $map;
                   ?>

                
                  
                    </div>
                </div>

         </div>
      </div>

    </div>

   <!-- Contact Us Section Ends -->

    <!-- Contact Us Details Starts -->

    <div class="bg-white contact-us-section-full">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4">
                    <div class="contact-item mt-40">
                        <div class="float-left">
                            <div class="contact-icon d-inline-block border rounded-pill shadow mt-1 mr-4">
                                <i class="fas fa-globe"></i>
                            </div>
                        </div>
                        <div class="contact-details">
                            <h4 class="text-dark mb-1">Website</h4>
                            <?php $website= DB::table('home-setting')->where('id',1)->pluck('contact_website')->first(); ?>
                          {!! $website !!}
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="contact-item mt-40">
                        <div class="float-left">
                            <div class="contact-icon d-inline-block border rounded-pill shadow mt-1 mr-4">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                        </div>
                        <div class="contact-details">
                        <h4 class="text-dark mb-1">Call us</h4>
                        <?php $call_us= DB::table('home-setting')->where('id',1)->pluck('contact_call_us')->first(); ?>
                          {!! $call_us !!}
                 
                    
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="contact-item mt-40">
                        <div class="float-left">
                            <div class="contact-icon d-inline-block border rounded-pill shadow mt-1 mr-4">
                                <i class="far fa-paper-plane"></i>
                            </div>
                        </div>
                        <div class="contact-details">
                            <h4 class="text-dark mb-1">Email</h4>
                            <?php $email= DB::table('home-setting')->where('id',1)->pluck('contact_email')->first(); ?>
                          {!! $email !!}
                 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Us Details Ends-->

</div>


<script>
// just for the demos, avoids form submit
$(function () {

jQuery.validator.addMethod("special", function(value, element) {
  return this.optional(element) || /^[a-z]+$/i.test(value);
}, "Special character and Number not allowed"); 

jQuery.validator.addMethod("no_space", function(value, element) {
  return this.optional(element) || /^\S*$/i.test(value);
}, "Space not allowed"); 

jQuery.validator.addMethod("not_same", function(value, element) {
  return this.optional(element) || /^(?=.{8,20}$)(([a-z0-9])\2?(?!\2))+$/i.test(value);
}, "Not same character allowed"); 

jQuery.validator.addMethod("let_space", function(value, element) {
  return this.optional(element) || /^[a-zA-Z_ ]*$/i.test(value);
}, "Letters and space allowed only "); 





jQuery.validator.addMethod("no_space_beg", function(value, element) {
  return this.optional(element) || /^[^\s]+(\s.*)?$/i.test(value);
}, "No space allowed in starting"); 


jQuery.validator.addMethod("all_zero", function(value, element) {
  return this.optional(element) || /\d{3}[1-9]/i.test(value);
}, "All number cannot Zero"); 






jQuery.validator.setDefaults({
  debug: true,
  success: "valid"
  
});
$( "#contact-us-form" ).validate({
   submitHandler: function(form) {


    grecaptcha.ready(function() {
          grecaptcha.execute('6LePicMZAAAAAC1ta45ya3GCurKvagU-nDW_xqJa', {action: 'submit'}).then(function(token) {
            form.submit();
          });
        });




  },

rules: {
   first_name: {
        required: true,
        minlength: 3,
        maxlength: 55,
        let_space:true, 
        no_space_beg:true,
    },

    last_name: {
        required: true,
        minlength: 3,
        maxlength: 55,
        let_space:true, 
        no_space_beg:true,
    },

    
    message: {
      required: true,
        minlength: 30,
        maxlength: 400,
    },
    email: {
      required: true,
      email: true,
      maxlength:30,
    },
    phone: {
      required: true,
      digits: true,
      minlength: 7,
      maxlength: 15,
      all_zero:true,

    },
  


},

});


});

</script>
@endsection