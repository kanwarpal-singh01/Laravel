@php $locale = App::getLocale(); @endphp
<?php $days = array(1=>__('messages.monday'), 2=>__('messages.tuesday'),3=>__('messages.wednesday'),4=>__('messages.thursday'),5=>__('messages.friday'),6=>__('messages.saturday'),7=>__('messages.sunday'));?>

<script src='https://www.google.com/recaptcha/api.js'></script>
<footer class="footer-new">
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
                    <div class="col-md-4">
                        <div class="epoxa-logo-det">
                            <div class="foot-logo">
                                <a href="{{url('/')}}"><img src="{{asset('img/logo.png')}}"></a>
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
                    <div class="col-md-4" style="padding-left:50px;">
                        <div class="footer-heading">
                            <h4>{{__('messages.our_link')}}</h4>
                        </div>
                        <div class="footer-link">
                            <ul>
                                <li><a href="{{url('agb-s')}}"><?php echo __('messages.agb_s');?></a></li>
                                <li><a
                                        href="{{url('privacy-policy')}}"><?php echo __('messages.home.privacy_policy');?></a>
                                </li>
                                <li><a href="{{url('impressum')}}"><?php echo __('messages.impressum');?></a></li>
                                <li><a href="{{url('newsletter')}}">{{__('messages.newsletter')}} </a></li>
                                <li><a href="{{url('career')}}">{{__('messages.career')}}</a></li>
                                <li><a href="{{url('contact-us')}}">{{__('messages.home.contact_us')}}</a>
                                </li>
                                <li><a href="{{url('auction/charts')}}">{{__('messages.precious_metals')}}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
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

        {{config('Settings.copy-right-text')}}
    </div>
</footer>
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
@include('includes.landing_footer_js')