@include('includes.site.head')

<div class="wrapper">
   <div class="bannerdiv">
      <div class="container">
         <div class="card-deck">
            <div class="col-xl-12">
               <div class="banner_content">
                  <h3>{!! $pageData->title_en !!}</h3>
               </div>
            </div>
         </div>
      </div>
   </div>

   <!-- How It Works Section Starts -->
   <div class="bg-light section-padding section-square-morphin">
      <div class="container">
         <div class="section-heading">
            {!! $pageData->content_en !!}

         </div>

      </div>
   </div>
   <!-- How It Works Section Ends -->


</div>