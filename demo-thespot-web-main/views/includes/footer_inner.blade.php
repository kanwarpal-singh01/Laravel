@php $url = Request::segments(); @endphp
@php $input = Request::query(); @endphp
@php $locale = App::getLocale();@endphp

<link rel="stylesheet" type="text/css" href="{{asset('landing/css/style-new.css')}}">
<?php $days = array(1=>__('messages.monday'), 2=>__('messages.tuesday'),3=>__('messages.wednesday'),4=>__('messages.thursday'),5=>__('messages.friday'),6=>__('messages.saturday'),7=>__('messages.sunday'));?>
<footer class="home_footer">
    <div class="wall-footer">
        <div class="row" style="margin:0;">
            <div class="col-md-4">
                <div class="footer-wall-bg">
                    <img src="{{asset('landing/img/new-images/footer1.jpg')}}">
                </div>
                <article>
                    <div class="footer-tit">
                        <h4>{{__('messages.universal.contact')}} <font>{{__('messages.us')}}</font>
                        </h4>
                    </div>
                    <div class="contact_list">
                        <ul>
                            <li>
                                <figure><i class="fas fa-map-marker-alt"></i></figure>
                                <figcaption>{{config('Settings.Address')}}</figcaption>
                            </li>
                            <li>
                                <figure><i class="fas fa-mobile-alt"></i></figure>
                                <figcaption><a
                                        href="tel:{{config('Settings.contact-no')}}">{{config('Settings.contact-no')}}</a>
                                </figcaption>
                            </li>
                            @if(config('Settings.phone-no') != '')
                            <li>
                                <figure><i class="fas fa-phone"></i></figure>
                                <figcaption><a
                                        href="tel:{{config('Settings.phone-no')}}">{{config('Settings.phone-no')}}</a>
                                </figcaption>
                            </li>
                            @endif
                            <li>
                                <figure><i class="fas fa-fax"></i></figure>
                                <figcaption>{{config('Settings.fax-number')}}</figcaption>
                            </li>
                            <li>
                                <figure><i class="fas fa-envelope-open"></i></figure>
                                <figcaption><a
                                        href="mailTo:{{config('Settings.AdminEmail')}}">{{config('Settings.AdminEmail')}}</a>
                                </figcaption>
                            </li>
                            <li>
                                <figure><i class="fas fa-globe"></i></figure>
                                <figcaption><a target="_blank"
                                        href="{{config('Settings.website')}}">{{config('Settings.website')}}</a>
                                </figcaption>
                            </li>

                        </ul>
                    </div>
                </article>
            </div>
            <div class="col-md-4">
                <div class="footer-wall-bg">
                    <img src="{{asset('landing/img/new-images/footer2.jpg')}}">
                </div>
                <article>
                    <div class="footer-tit">
                        <h4>{{__('messages.opening')}} <font>{{__('messages.hours')}}</font>
                        </h4>
                    </div>
                    <div class="opening-hours">
                        <ul>
                            <?php 
                   use App\OpeningHr;
                   $openingHrs = OpeningHr::where('user_id',1)->first();
                   ?>
                            @if(!empty($openingHrs))
                            <?php 
                      $from = @unserialize($openingHrs['opening_from']);
                      $to  = @unserialize($openingHrs['opening_to']);
                    ?>
                            @foreach($days as $key => $day)
                            <li>
                                <span>{{$day}}</span>
                                @if($from[$key] != "" && $to[$key] != "")
                                <span>{{$from[$key]}}–{{$to[$key]}}</span>
                                @else
                                <span>{{__('messages.closed')}}</span>
                                @endif
                            </li>
                            @endforeach
                            @endif
                        </ul>
                    </div>
                </article>
            </div>
            <div class="col-md-4">
                <div class="footer-wall-bg">
                    <img src="{{asset('landing/img/new-images/footer3.jpg')}}">
                </div>
                <article>
                    <form id="contact-us">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="footer-tit">
                            <h4>{{__('messages.write')}} <font>{{__('messages.us')}}</font>
                            </h4>
                        </div>
                        <div class="shiping_form_b2b"></div>
                        <div class="footer-tell-us">
                            <div class="form-group">
                                <input type="text" class="form-control" name="first_name"
                                    placeholder="{{__('messages.placeholder.first_name')}}">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="last_name"
                                    placeholder="{{__('messages.placeholder.last_name')}}">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" name="email"
                                    placeholder="{{__('messages.placeholder.email')}}">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="message"
                                    placeholder="{{__('messages.placeholder.message')}}"></textarea>
                            </div>
                            <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="{{config('Settings.google-captcha-key')}}">
                                </div>
                                <span class="" id="disError" style="display:none;color: red;">Please select captcha
                                </span>
                            </div>
                            @if ($errors->has('g-recaptcha-response'))
                            <div class="help-block">
                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                            </div>
                            @endif
                            <div class="overlay">
                                <img src="{{url('/')}}/img/loading.gif" width="25px">
                            </div>
                            <button class="btn btn-radius btn-yellow">{{__('messages.submit')}}</button>
                        </div>
                    </form>
                </article>
            </div>
        </div>
    </div>
    <div class="footer-last">
        <div class="footer-last-in">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <div class="epoxa-logo-det">
                            <div class="foot-logo">
                                <a href="{{url('auction')}}"><img alt="Logo"
                                        src="{{asset('front/img/footer_logo.png')}}" /></a>
                            </div>
                            @if($locale == "en")
                            <p>{{config('Settings.Description')}} </p>
                            @else
                            <p>{{config('Settings.description_de')}} </p>
                            @endif
                        </div>
                        <div class="footer-icon">
                            <a href="{{config('Settings.facebook-link')}}" target="_blank"><img
                                    src="{{asset('landing/img/new-images/foot-icon1.png')}}"></a>
                            <a href="{{config('Settings.twitter-link')}}" target="_blank"><img
                                    src="{{asset('landing/img/new-images/foot-icon2.png')}}"></a>
                            <a href="{{config('Settings.linkedin-link')}}" target="_blank"><img
                                    src="{{asset('landing/img/new-images/foot-icon3.png')}}"></a>
                            <a href="{{config('Settings.insta-link')}}" target="_blank"><img
                                    src="{{asset('landing/img/new-images/foot-icon4.png')}}"></a>
                            <a href="{{url('blog')}}" target="_blank"><img
                                    src="{{asset('landing/img/new-images/foot-icon5.png')}}"></a>
                        </div>
                    </div>
                    @php $category = Helper::root_categories();@endphp
                    <div class="col-md-3">
                        <div class="footer-heading">
                            <h4><?php echo __('messages.footer_inner.categories');?></h4>
                        </div>
                        @if(isset($category) && count($category) > 0)
                        <div class="footer-link">
                            <ul>
                                @foreach($category as $cat)
                                @if($locale == "en")
                                <li><a class="{{(isset($input['category']) && $input['category'] == $cat['id'])? 'active' : ''}}"
                                        href="{{url('listing?category='.$cat['id'])}}">{{$cat['title_en']}}</a></li>
                                @else
                                <li><a class="{{(isset($input['category']) && $input['category'] == $cat['id'])? 'active' : ''}}"
                                        href="{{url('listing?category='.$cat['id'])}}">{{$cat['title_de']}}</a></li>
                                @endif
                                @endforeach
                                <li><a href="{{url('listing')}}"><?php echo __('messages.footer_inner.more');?></a></li>
                            </ul>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <div class="footer-heading">
                            <h4><?php echo __('messages.footer_inner.useful_links');?></h4>
                        </div>
                        <div class="footer-link">
                            <ul>
                                <li><a href="{{url('auction')}}"
                                        class="{{(isset($url[0]) && $url[0] == 'home')? 'active':''}}"><?php echo __('messages.header_inner.home');?></a>
                                </li>
                                <li><a href="{{url('aboutus')}}"><?php echo __('messages.header_inner.about_us');?></a>
                                </li>
                                <li><a
                                        href="{{url('contact-us')}}"><?php echo __('messages.header_inner.contact_us');?></a>
                                </li>
                                <li>
                                    <a href="{{url('topselling')}}"
                                        class="{{(isset($url[0]) && $url[0] == 'topselling')? 'active':''}}"><?php echo __('messages.top_selling_products');?>
                                    </a>
                                </li>
                                @php $footer = DB::table("cmspage")->where(['status'=>1, 'menu_type'=>2])->get();
                                @endphp
                                @foreach($footer as $row)
                                @if($locale == "en")
                                <li><a href="{{url('/')}}/{{$row->slug}}"
                                        class="{{(isset($url[2]) && $url[2] == $row->slug)? 'active':''}}">{{$row->title_en}}</a>
                                </li>
                                @else
                                <li><a href="{{url('/')}}/{{$row->slug}}"
                                        class="{{(isset($url[2]) && $url[2] == $row->slug)? 'active':''}}">{{$row->title_de}}</a>
                                </li>
                                @endif
                                @endforeach
                                <li><a href="{{url('newsletter')}}"
                                        class="{{(isset($url[1]) && $url[1] == $row->slug)? 'newselleter':''}}">{{__('messages.newsletter')}}</a>
                                </li>
                                <li><a href="{{url('reports')}}"
                                        class="{{(isset($url[1]) && $url[1] == $row->slug)? 'report':''}}">{{__('messages.report')}}</a>
                                </li>
                                <li><a href="{{url('auction/charts')}}">{{__('messages.precious_metals')}}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="footer-right">
                            <img src="{{asset('landing/img/new-images/footer-logo.png')}}">
                            <p>Mitglied der Initiative "Fairness im Handel".
                                Informationen zur Initiative: </p>
                            <p><a href="https://www.fairness-im-handel.de"
                                    target="_blank">https://www.fairness-im-handel.de</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <p>{{config('Settings.copy-right-text')}}</p>
    </div>
</footer>
</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    //For Calculater
    $("#contact-us").validate({
        rules: {
            'first_name': {
                required: {
                    depends: function() {
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                }
            },
            'last_name': {
                required: {
                    depends: function() {
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                }
            },
            'email': {
                required: {
                    depends: function() {
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                }
            },
            'message': {
                required: {
                    depends: function() {
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                }
            },
            'g-recaptcha-response': 'required',

        },
        messages: {
            first_name: "<?php echo __('validation.required',['attribute'=> __('validation.attribute.first_name')]); ?>",
            last_name: "<?php echo __('validation.required',['attribute'=> __('validation.attribute.last_name')]); ?>",
            email: "<?php echo __('validation.required',['attribute'=> __('validation.attribute.email')]); ?>",
            message: "<?php echo __('validation.required',['attribute'=> __('validation.attribute.message')]); ?>",
            'g-recaptcha-response': "<?php echo __('validation.required',['attribute'=> __('validation.attribute.g-recaptcha-response')]); ?>",
        },
        errorElement: "span",
        errorPlacement: function(label, element) {
            label.addClass('errorMsq');
            element.parent().append(label);
        },

        highlight: function(element, errorClass, validClass) {
            $(element).parents(".col-sm-5").addClass("has-error").removeClass("has-success");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents(".col-sm-5").addClass("has-success").removeClass("has-error");
        },
        submitHandler: function(form) {
            //console.log($('#contact-us').serialize());
            $.ajax({
                type: 'POST',
                url: '{{url("contact-us")}}',
                data: $('#contact-us').serialize(),
                beforeSend: function() {
                    $('.overlay').css('display', 'block');
                },
                success: function(data) {
                    $('.overlay').hide();
                    if (data.code == 1) {
                        $('.shiping-result').text('');
                        var result =
                            '<div class="shiping-result" style="color:white;">' + data
                            .messages + '</div>';
                        $(result).insertAfter('.shiping_form_b2b');
                        setTimeout(function() {
                            $('.shiping-result').text('');
                        }, 20000);
                        document.getElementById('contact-us').reset();


                    } else {
                        $('.shiping-result').text('');
                        var result = '<div class="shiping-result" style="color:red;">' +
                            data.messages + '</div>';
                        $(result).insertAfter('.shiping_form_b2b');
                        setTimeout(function() {
                            $('.shiping-result').text('');
                        }, 20000);
                    }

                }
            });
            return false;
        }
    });
});
</script>
<style type="text/css">
iframe {
    display: block;
}
</style>