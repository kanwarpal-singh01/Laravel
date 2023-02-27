@extends('layouts.site')
@section('content')

<div class="wrapper">
    <div class="bannerdiv">
        <div class="container">
            <div class="card-deck">
                <div class="col-xl-12">
                    <div class="banner_content">
                        <h3> FAQ</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>






                  
    <!-- ......Faq Section....... -->
    <section class="faq-section bg-light section-padding section-square-morphin">
        <div class="container">
            <div class="accordion" id="faqaccordion">
 
           
           
            @foreach($faqs as $faq)
            <div class="card">
                <div class="card-header" id="heading{{$faq->id}}">
                <h2 class="mb-0">
                    <button
                        class="btn btn-link collapsed"
                        type="button"
                        data-toggle="collapse"
                        data-target="#collapse{{$faq->id}}"
                        aria-expanded="false"
                        aria-controls="collapse{{$faq->id}}"
                        >
                  {{ $faq->question}}
                    </button>
                </h2>
                </div>
                <div
                id="collapse{{$faq->id}}"
                class="collapse"
                aria-labelledby="heading{{$faq->id}}"
                data-parent="#faqaccordion"
                >
                <div class="card-body">
                {{ $faq->answer}}
                </div>
                </div>
            </div>
            @endforeach



  
        
      
            </div>
        </div>
    </section>
    <!-- ......Faq Section....... -->


</div>

@endsection

