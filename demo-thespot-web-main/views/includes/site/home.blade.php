@extends('layouts.site')
@section('content')
<div class="wrapper">
   <!-- Banner Section Starts -->
   <div class="banner-slider owl-slider">
      <div id="banner_slide" class="owl-carousel">
         <div class="item">
            <img src="{{ config('app.asset_url') }}/assets/img/banner-bg.png" alt=""/>
            <div class="container">
               <div class="card-deck">
                  <div class="banner-content">
                     <div class="banner-c-text">
                        <h3>Lorem ipsum dolor <span class="blockspan">consectetur adipiscing elit</span></h3>
                        <p>There is a powerful need for symbolism, and that means the architecture must have <span class="blockspan">something that appeals to the human heart.</span></p>
                        <div class="banner-btn">
                           <button class="btn">Know More</button>
                           <button class="btn btn-orange">Contact Us</button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="item">
            <img src="{{ config('app.asset_url') }}/assets/img/banner-bg.png" alt=""/>
            <div class="container">
               <div class="card-deck">
                  <div class="banner-content">
                     <div class="banner-c-text">
                        <h3>Lorem ipsum dolor <span class="blockspan">consectetur adipiscing elit</span></h3>
                        <p>There is a powerful need for symbolism, and that means the architecture must have <span class="blockspan">something that appeals to the human heart.</span></p>
                        <div class="banner-btn">
                           <button class="btn">Know More</button>
                           <button class="btn btn-orange">Contact Us</button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="item">
            <img src="{{ config('app.asset_url') }}/assets/img/banner-bg.png" alt=""/>
            <div class="container">
               <div class="card-deck">
                  <div class="banner-content">
                     <div class="banner-c-text">
                        <h3>Lorem ipsum dolor <span class="blockspan">consectetur adipiscing elit</span></h3>
                        <p>There is a powerful need for symbolism, and that means the architecture must have <span class="blockspan">something that appeals to the human heart.</span></p>
                        <div class="banner-btn">
                           <button class="btn">Know More</button>
                           <button class="btn btn-orange">Contact Us</button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- Banner Section Ends -->
   <!-- About Us Section Starts -->
   <div class="about-us-section section-padding section-square-morphin">
      <div class="container">
         <div class="section-heading">
            <span class="top-small-text">who we are</span>
            <h3 class="section-h-title">THE STORY ABOUT US</h3>
            <p>There is a powerful need for symbolism, and that means the architecture must have something that appeals to the human heart.</p>
         </div>
         <div class="card-deck">
            <div class="col-xl-6 col-sm-6">
               <div class="about-us-img">
                  <img src="{{ config('app.asset_url') }}/assets/img/story-left.png">
               </div>
            </div>
            <div class="col-xl-6 col-sm-6">
               <div class="about-us-content">
                  <h3>Sed ut perspiciatis unde omnis iste natus error sit</h3>
                  <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. </p>
                  <button class="know-more-btn btn">know more</button>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- About Us Section Ends -->
   <!-- How It Works Section Starts -->
   <div class="how-it-works section-padding section-square-morphin">
      <div class="container">
         <div class="section-heading">
            <h3 class="section-h-title">How it works</h3>
            <p>There is a powerful need for symbolism, and that means the architecture must have something that appeals to the human heart.</p>
         </div>
         <ul class="work-steps">
            <li>
               <figure>
                  <img src="{{ config('app.asset_url') }}/assets/img/works-1.png">
               </figure>
               <h5>Lorem ipsum dolor sit</h5>
               <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, Lorem ipsum dolor sit amet, consetetur sadipscing elitr,</p>
            </li>
            <li>
               <figure>
                  <img src="{{ config('app.asset_url') }}/assets/img/works-2.png">
               </figure>
               <h5>Lorem ipsum dolor sit</h5>
               <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, Lorem ipsum dolor sit amet, consetetur sadipscing elitr,</p>
            </li>
            <li>
               <figure>
                  <img src="{{ config('app.asset_url') }}/assets/img/works-1.png">
               </figure>
               <h5>Lorem ipsum dolor sit</h5>
               <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, Lorem ipsum dolor sit amet, consetetur sadipscing elitr,</p>
            </li>
            <li>
               <figure>
                  <img src="{{ config('app.asset_url') }}/assets/img/works-2.png">
               </figure>
               <h5>Lorem ipsum dolor sit</h5>
               <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, Lorem ipsum dolor sit amet, consetetur sadipscing elitr,</p>
            </li>
         </ul>
         <button class="know-more-btn btn">know more</button>
      </div>
   </div>
   <!-- How It Works Section Ends -->
   <!-- Our Top Client Section Starts -->
   <div class="our-client-section section-padding section-square-morphin">
      <div class="section-heading">
         <h3 class="section-h-title">OUR TOP Recruiters</h3>
         <p>There is a powerful need for symbolism, and that means the architecture must have something that appeals to the human heart.</p>
      </div>
      <div class="container">
         <ul class="our-client-list">
            <li>
               <figure>
                  <img src="{{ config('app.asset_url') }}/assets/img/our-top-2.png">
               </figure>
               <div class="client-text">
                  <h5>Lorem ipsum dolor sit</h5>
                  <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr,</p>
                  <div class="rating-span">
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="far fa-star"></i></span>
                     <span><i class="far fa-star"></i></span>
                  </div>
                  <button class="btn">Know More</button>
               </div>
            </li>
            <li>
               <figure>
                  <img src="{{ config('app.asset_url') }}/assets/img/contact-us-img.png">
               </figure>
               <div class="client-text">
                  <h5>Lorem ipsum dolor sit</h5>
                  <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr,</p>
                  <div class="rating-span">
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="far fa-star"></i></span>
                     <span><i class="far fa-star"></i></span>
                  </div>
                  <button class="btn">Know More</button>
               </div>
            </li>
            <li>
               <figure>
                  <img src="{{ config('app.asset_url') }}/assets/img/contact-us-img.png">
               </figure>
               <div class="client-text">
                  <h5>Lorem ipsum dolor sit</h5>
                  <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr,</p>
                  <div class="rating-span">
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="far fa-star"></i></span>
                     <span><i class="far fa-star"></i></span>
                  </div>
                  <button class="btn">Know More</button>
               </div>
            </li>
            <li>
               <figure>
                  <img src="{{ config('app.asset_url') }}/assets/img/our-top-2.png">
               </figure>
               <div class="client-text">
                  <h5>Lorem ipsum dolor sit</h5>
                  <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr,</p>
                  <div class="rating-span">
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="far fa-star"></i></span>
                     <span><i class="far fa-star"></i></span>
                  </div>
                  <button class="btn">Know More</button>
               </div>
            </li>
            <li>
               <figure>
                  <img src="{{ config('app.asset_url') }}/assets/img/our-top-2.png">
               </figure>
               <div class="client-text">
                  <h5>Lorem ipsum dolor sit</h5>
                  <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr,</p>
                  <div class="rating-span">
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="far fa-star"></i></span>
                     <span><i class="far fa-star"></i></span>
                  </div>
                  <button class="btn">Know More</button>
               </div>
            </li>
            <li>
               <figure>
                  <img src="{{ config('app.asset_url') }}/assets/img/our-top-2.png">
               </figure>
               <div class="client-text">
                  <h5>Lorem ipsum dolor sit</h5>
                  <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr,</p>
                  <div class="rating-span">
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="far fa-star"></i></span>
                     <span><i class="far fa-star"></i></span>
                  </div>
                  <button class="btn">Know More</button>
               </div>
            </li>
            <li>
               <figure>
                  <img src="{{ config('app.asset_url') }}/assets/img/our-top-2.png">
               </figure>
               <div class="client-text">
                  <h5>Lorem ipsum dolor sit</h5>
                  <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr,</p>
                  <div class="rating-span">
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="far fa-star"></i></span>
                     <span><i class="far fa-star"></i></span>
                  </div>
                  <button class="btn">Know More</button>
               </div>
            </li>
            <li>
               <figure>
                  <img src="{{ config('app.asset_url') }}/assets/img/our-top-2.png">
               </figure>
               <div class="client-text">
                  <h5>Lorem ipsum dolor sit</h5>
                  <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr,</p>
                  <div class="rating-span">
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="fas fa-star"></i></span>
                     <span><i class="far fa-star"></i></span>
                     <span><i class="far fa-star"></i></span>
                  </div>
                  <button class="btn">Know More</button>
               </div>
            </li>
         </ul>
         <button class="know-more-btn btn">know more</button>
      </div>
   </div>
   <!-- Our Top Client Section Ends -->
   <!-- Our Top Companies Section Starts -->
   <div class="our-companies-section section-padding section-square-morphin">
      <div class="section-heading">
         <h3 class="section-h-title">OUR TOP Recruiters</h3>
         <p>There is a powerful need for symbolism, and that means the architecture must have something that appeals to the human heart.</p>
      </div>
      <div class="container">
         <div class="card-deck">
            <div class="col-xl-6">
               <ul class="our-comp-img">
                  <li class="bottom-border-fadded-left"><img src="{{ config('app.asset_url')}}/assets/img/our-client-1.png"></li>
                  <li><img src="{{ config('app.asset_url')}}/assets/img/our-client-2.png"></li>
                  <li><img src="{{ config('app.asset_url')}}/assets/img/our-client-3.png"></li>
                  <li class="no-right-border bottom-border-fadded-right"><img src="{{ config('app.asset_url')}}assets/img/our-client-4.png"></li>
                  <li class="bottom-border-fadded-left"><img src="{{ config('app.asset_url')}}/assets/img/our-client-5.png"></li>
                  <li><img src="{{ config('app.asset_url')}}/assets/img/our-client-6.png"></li>
                  <li><img src="{{ config('app.asset_url')}}/assets/img/our-client-7.png"></li>
                  <li class="no-right-border bottom-border-fadded-right"><img src="{{ config('app.asset_url')}}/assets/img/our-client-8.png"></li>
                  <li  class="bottom-border-fadded-left"><img src="{{ config('app.asset_url')}}/assets/img/our-client-9.png"></li>
                  <li><img src="{{ config('app.asset_url')}}/assets/img/our-client-10.png"></li>
                  <li><img src="{{ config('app.asset_url')}}/assets/img/our-client-11.png"></li>
                  <li class="no-right-border bottom-border-fadded-right"><img src="{{ config('app.asset_url')}}/assets/img/our-client-3.png"></li>
                  <li class="no-bottom-border"><img src="{{ config('app.asset_url')}}/assets/img/our-client-6.png"></li>
                  <li class="no-bottom-border"><img src="{{ config('app.asset_url')}}/assets/img/our-client-1.png"></li>
                  <li class="no-bottom-border"><img src="{{ config('app.asset_url')}}/assets/img/our-client-8.png"></li>
                  <li class="no-right-border no-bottom-border"><img src="{{ config('app.asset_url')}}/assets/img/our-client-2.png"></li>
               </ul>
            </div>
            <div class="col-xl-6">
               <div class="our-comp-content about-us-content">
                  <h3>Sed ut perspiciatis unde omnis iste natus error sit</h3>
                  <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. </p>
                  <button class="know-more-btn btn">know more</button>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- Our Top Companies Section Ends -->
   <!-- FAQ Section Starts -->
   <div class="faq-section section-padding">
      <div class="section-heading">
         <h3 class="section-h-title">frequently asked questions</h3>
         <p>There is a powerful need for symbolism, and that means the architecture must have something that appeals to the human heart.</p>
      </div>
      <div class="container">
         <div class="card-deck">
            <div class="col-xl-6">
               <div class="faq-img">
                  <img src="{{ config('app.asset_url') }}/assets/img/faq-left-img.png">
               </div>
            </div>
            <div class="col-xl-6">
               <div class="faq-acc accordion" id="accordionFaq">
                  <div class="card">
                     <div class="card-header" id="headingOne">
                        <h2 class="mb-0">
                           <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                           Lorem ipsum dolor sit amet, consetetur sadipscing elitr
                           </button>
                        </h2>
                     </div>
                     <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionFaq">
                        <div class="card-body">
                           Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                           <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                           Lorem ipsum dolor sit amet, consetetur sadipscing elitr
                           </button>
                        </h2>
                     </div>
                     <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionFaq">
                        <div class="card-body">
                           Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-header" id="headingThree">
                        <h2 class="mb-0">
                           <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                           Lorem ipsum dolor sit amet, consetetur sadipscing elitr
                           </button>
                        </h2>
                     </div>
                     <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionFaq">
                        <div class="card-body">
                           Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- FAQ Section Ends -->
   <!-- Contact Us Section Starts -->
   <div class="contact-us-section section-padding section-square-morphin">
      <div class="section-heading">
         <h3 class="section-h-title">Get in touch with us</h3>
         <p>There is a powerful need for symbolism, and that means the architecture must have something that appeals to the human heart.</p>
      </div>
      <div class="container">
         <div class="card-deck">
            <div class="col-xl-6">
               <div class="contact-form">
                  <form>
                     <div class="form-group flex-group">
                        <input type="text" class="form-control" placeholder="First Name"/>
                        <input type="text" class="form-control" placeholder="Last Name"/>
                     </div>
                     <div class="form-group">
                        <input type="text" class="form-control" placeholder="Phone Number"/>
                     </div>
                     <div class="form-group">
                        <input type="text" class="form-control" placeholder="You Email Address"/>
                     </div>
                     <div class="form-group">
                        <textarea type="text" class="form-control" placeholder="Type Your Message"></textarea>
                     </div>
                     <button class="btn know-more-btn">Know more</button>
                  </form>
               </div>
            </div>
            <div class="col-xl-6">
               <div class="contact-img">
                  <img src="{{ config('app.asset_url') }}/assets/img/contact-us-img.png">
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- Contact Us Section Ends -->
</div>
</div>

@endsection