<?php $url = Request::segments();$urlll = Request::all();
use App\Shop;

$shop = Shop::where('user_id',Auth::user()->id)->first();
   $getPakage = Helper::getPakegeInfo();

?>
<div class="dashboard_menu">
    <div class="container">
        <nav class="navbar navbar-expand-lg scrolling-navbar">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent45"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent45">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item @if(isset($url[1]) && $url[1]=='dashboard') {{'active'}}  @endif"><a
                            class="nav-link"
                            href="{{url('/')}}/{{$url[0].'/dashboard'}}"><?php echo __('messages.dashboard_menu.dashboard');?></a>
                    </li>

                    <!--  <li class="nav-item @if(isset($url[1]) && $url[1]=='updateprofile') {{'active'}}  @endif"><a class="nav-link" href="{{url('/')}}/{{$url[0].'/updateprofile'}}"><?php //echo __('messages.dashboard_menu.my_account');?></a></li> -->

                    <li
                        class="nav-item @if((isset($url[1]) && $url[1]=='my_products') || (isset($url[1]) && $url[1] == 'products') ||(isset($url[1]) && $url[1]=='drafted_products') || $url[1]=='favouritelist')  {{'active'}}  @endif">

                        <div class="dropdown">
                            <button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
                                <a class="nav-link waves-effect waves-light"
                                    href="javascript:;"><?php echo __('messages.dashboard_menu.my_listing');?></a> <span
                                    class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">

                                <li><a title="<?php echo __('messages.dashboard_menu.my_listing');?>"
                                        href="{{url(Auth::user()->role.'/my_products')}}"
                                        class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='my_products') {{'active'}}  @endif"><?php echo __('messages.dashboard_menu.my_listing');?></a>
                                </li>

                                <li><a title="<?php echo __('messages.dashboard_menu.drafted_products');?>"
                                        href="{{url(Auth::user()->role.'/drafted_products')}}"
                                        class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='drafted_products'){{'active'}}@endif"><?php echo __('messages.dashboard_menu.drafted_products');?></a>
                                </li>

                                @if(Auth::user()->role == 'customer')
                                <li><a class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='favouritelist'){{'active'}}@endif"
                                        href="{{url(Auth::user()->role.'/favouritelist')}}"><?php echo __('messages.dashboard_menu.favourite_list');?></a>
                                </li>
                                @endif
                                <li><a class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='customerfavlist'){{'active'}}@endif"
                                        href="{{url(Auth::user()->role.'/customerfavlist')}}"><?php echo __('messages.dashboard_menu.customer_fav_list');?></a>
                                </li>

                            </ul>
                        </div>
                    </li>

                    <!--  <li class="nav-item @if(isset($url[1]) && $url[1]=='updateprofile' || isset($url[1]) && $url[1]=='sellingsettings') {{'active'}}  @endif">
	   	
	   	<div class="dropdown">
		  <button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
		   <a class="nav-link waves-effect waves-light" href="javascript:;"><?php echo __('messages.dashboard_menu.my_account');?></a> <span class="caret"></span>
		  </button>
		  <ul class="dropdown-menu">
		  	@if(Auth::user()->role == "customer")
			<li><a title="<?php echo __('messages.dashboard_menu.personal');?>"  href="{{url('/')}}/{{$url[0].'/updateprofile'}}" class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='updateprofile') {{'active'}}  @endif"><?php echo __('messages.dashboard_menu.personal');?></a></li>
			@endif
			<li><a title="<?php echo __('messages.dashboard_menu.selling_settings');?>" href="{{url('/')}}/{{$url[0].'/sellingsettings'}}" class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='sellingsettings'){{'active'}}@endif"><?php echo __('messages.dashboard_menu.selling_settings');?></a></li>
			
		  </ul>
		</div> 
	   </li> -->

                    <li class="nav-item @if(isset($url[1]) && $url[1]=='updateprofile' ) {{'active'}}  @endif "><a
                            title="<?php echo __('messages.dashboard_menu.my_account');?>"
                            href="{{url('/')}}/{{$url[0].'/updateprofile'}}"
                            class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='updateprofile') {{'active'}}  @endif"><?php echo __('messages.dashboard_menu.my_account');?></a>
                    </li>

                    <li
                        class="nav-item @if(isset($url[1]) && $url[1]=='myoffers' || $url[1]=='mybids') {{'active'}}  @endif">

                        <div class="dropdown">
                            <button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
                                <a class="nav-link waves-effect waves-light"
                                    href="javascript:;"><?php echo __('messages.dashboard_menu.my_offers');?></a> <span
                                    class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                @if(Auth::user()->role == "customer")
                                <li><a title="<?php echo __('messages.dashboard_menu.sent_offers');?>"
                                        href="{{url(Auth::user()->role.'/myoffers/sent')}}"
                                        class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='myoffers' &&  isset($url[2]) && $url[2]=='sent' ){{'active'}}@endif"><?php echo __('messages.dashboard_menu.sent_offers');?></a>
                                </li>
                                @endif
                                <li><a title="<?php echo __('messages.dashboard_menu.received_offers');?>"
                                        href="{{url(Auth::user()->role.'/myoffers/recieved')}}"
                                        class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='myoffers' &&  (isset($url[2]) && $url[2]=='recieved' || isset($url[2]) && $url[2]=='view')){{'active'}}@endif"><?php echo __('messages.dashboard_menu.received_offers');?></a>
                                </li>

                                @if(Auth::user()->role == "customer")
                                <li><a title="<?php echo __('messages.dashboard_menu.sent_bids');?>"
                                        href="{{url(Auth::user()->role.'/mybids/sent')}}"
                                        class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='mybids' && isset($url[2]) && $url[2]=='sent' ){{'active'}}@endif"><?php echo __('messages.dashboard_menu.sent_bids');?></a>
                                </li>
                                @endif
                                <li><a title="<?php echo __('messages.dashboard_menu.received_bids');?>"
                                        href="{{url(Auth::user()->role.'/mybids/recieved')}}"
                                        class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='mybids' && isset($url[2]) && $url[2]=='recieved'){{'active'}}@endif"><?php echo __('messages.dashboard_menu.received_bids');?></a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- <li class="nav-item @if(isset($url[1]) && $url[1]=='mybids') {{'active'}}  @endif">
	   
	   <div class="dropdown">
		  <button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
		   <a class="nav-link waves-effect waves-light" href="javascript:;"><?php echo __('messages.dashboard_menu.my_bids');?></a> <span class="caret"></span>
		  </button>
		  <ul class="dropdown-menu">
		  	@if(Auth::user()->role == "customer")
			<li><a title="<?php echo __('messages.dashboard_menu.sent_bids');?>" href="{{url(Auth::user()->role.'/mybids/sent')}}" class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='mybids' && isset($url[2]) && $url[2]=='sent' ){{'active'}}@endif"><?php echo __('messages.dashboard_menu.sent_bids');?></a></li>
			@endif
			<li><a title="<?php echo __('messages.dashboard_menu.received_bids');?>" href="{{url(Auth::user()->role.'/mybids/recieved')}}" class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='mybids' && isset($url[2]) && $url[2]=='recieved'){{'active'}}@endif"><?php echo __('messages.dashboard_menu.received_bids');?></a></li>
			
		  </ul>
		</div> 
	   </li> -->

                    <!-- @if(Auth::user()->role == 'customer')
	   		<li class="nav-item @if(isset($url[1]) && $url[1]=='favouritelist') {{'active'}}  @endif"><a class="nav-link" href="{{url(Auth::user()->role.'/favouritelist')}}"><?php echo __('messages.dashboard_menu.favourite_list');?></a></li>
	   	@endif -->
                    <li class="nav-item @if(isset($url[1]) && $url[1]=='chat') {{'active'}}  @endif"><a class="nav-link"
                            href="{{url(Auth::user()->role.'/chat')}}"><?php echo __('messages.dashboard_menu.messages');?>@if(Helper::unreadmessage(Auth::user()->id)
                            != 0)<span
                                class="text-coloro">[{{Helper::unreadmessage(Auth::user()->id)}}]@endif</span></a></li>

                    <li
                        class="nav-item @if(isset($url[1]) && $url[1]=='purchase' || isset($url[1]) && $url[1]=='selling') {{'active'}}  @endif ">
                        <div class="dropdown">
                            <button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
                                <a class="nav-link waves-effect waves-light"
                                    href="javascript:;"><?php echo __('messages.dashboard_menu.my_transactions');?></a>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                @if(Auth::user()->role == "customer")
                                <li class=""><a title="<?php echo __('messages.dashboard_menu.my_purchase');?>"
                                        href="{{url(Auth::user()->role.'/purchase')}}"
                                        class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='purchase' ){{'active'}}@endif"><?php echo __('messages.dashboard_menu.my_purchase');?></a>
                                </li>
                                @endif
                                <li class="@if(isset($url[1]) && $url[1]=='selling'){{'active'}}@endif"><a
                                        title="<?php echo __('messages.dashboard_menu.my_sales');?>"
                                        href="{{url(Auth::user()->role.'/selling')}}"
                                        class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='selling'){{'active'}}@endif"><?php echo __('messages.dashboard_menu.my_sales');?></a>
                                </li>

                            </ul>
                        </div>
                    </li>

                    <li
                        class="nav-item @if(isset($url[1]) && $url[1]=='paymenthistory' || isset($url[1]) && $url[1]=='managecards' ||  isset($url[1]) && $url[1]=='addcard' || isset($url[1]) && $url[1]=='paymenthistoryi' ){{'active'}}@endif">
                        <div class="dropdown">
                            <button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
                                <a class="nav-link"
                                    href="{{url(Auth::user()->role.'/paymenthistory')}}"><?php echo __('messages.dashboard_menu.payment_history');?></a>
                            </button>
                            <ul class="dropdown-menu">

                                @if(Auth::user()->role == "customer")
                                <!-- <li >
			<a title="<?php //echo __('messages.dashboard_menu.manage_cards');?>" href="{{url('/').'/'.Auth::user()->role.'/managecards'}}" class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='managecards' || $url[1]=='addcard'){{'active'}}@endif"><?php //echo __('messages.dashboard_menu.manage_cards');?></a></li> -->

                                <li><a title="<?php echo __('messages.dashboard_menu.payment_history');?>"
                                        href="{{url(Auth::user()->role.'/paymenthistory')}}"
                                        class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='paymenthistory'){{'active'}}@endif"><?php echo __('messages.dashboard_menu.payment_history');?></a>
                                </li>
                                <li><a title="<?php echo __('messages.dashboard_menu.incoming_payment_history');?>"
                                        href="{{url(Auth::user()->role.'/paymenthistoryi')}}"
                                        class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='paymenthistoryi'){{'active'}}@endif"><?php echo __('messages.dashboard_menu.incoming_payment_history');?></a>
                                </li>

                                <li><a title="<?php echo __('messages.giftcodes');?>"
                                        href="{{url(Auth::user()->role.'/gifecodes')}}"
                                        class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='gifecodes'){{'active'}}@endif"><?php echo __('messages.giftcodes');?></a>
                                </li>
                                @endif
                                @if(Auth::user()->role == "vendor")
                                <li><a title="<?php echo __('messages.dashboard_menu.payment_history');?>"
                                        href="{{url(Auth::user()->role.'/paymenthistoryi')}}"
                                        class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='paymenthistoryi'){{'active'}}@endif"><?php echo __('messages.dashboard_menu.payment_history');?></a>
                                </li>

                                @endif

                            </ul>
                        </div>
                    </li>
                    <!-- <li class="nav-item @if(isset($url[1]) && $url[1]=='ratinglist'){{'active'}}@endif"><a class="nav-link" href="{{url(Auth::user()->role.'/ratinglist')}}"><?php //echo __('messages.dashboard_menu.ratings');?></a></li> -->
                    <li
                        class="nav-item @if(isset($url[1]) && ($url[1]=='ratinglist' || $url[1]=='ratinglist-sent' || $url[1]=='ratinglist-ascustomer-sent' || $url[1]=='ratingsListAsCustomer')){{'active'}}@endif">
                        <div class="dropdown">
                            <button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
                                <a class="nav-link waves-effect waves-light" href="javascript:;"
                                    title="<?php echo __('messages.dashboard_menu.ratings');?>"><?php echo __('messages.dashboard_menu.ratings');?></a>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li class=""><a title="vendor"
                                        class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='ratinglist'){{'active'}}@endif"
                                        href="{{url(Auth::user()->role.'/ratinglist')}}">Vendor Received</a>
                                </li>


                                <li class=""><a title="Vendor Sent"
                                        class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='ratinglist-sent'){{'active'}}@endif"
                                        href="{{url(Auth::user()->role.'/ratinglist-sent')}}">Vendor Sent</a>
                                </li>

                                <li class=""><a title="Customer Received"
                                        class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='ratingsListAsCustomer'){{'active'}}@endif"
                                        href="{{url(Auth::user()->role.'/ratinglist-as-customer')}}">Customer
                                        Received</a>
                                </li>

                                <li class=""><a title="Customer Sent"
                                        class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='ratinglist-ascustomer-sent'){{'active'}}@endif"
                                        href="{{url(Auth::user()->role.'/ratinglist-ascustomer-sent')}}">Customer
                                        Sent</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li
                        class="nav-item @if(isset($url[1]) && $url[1]=='invoicing' ||  $url[1]=='invoiceform' || $url[1]=='sellerinvoices'){{'active'}}@endif">
                        <div class="dropdown">
                            <button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
                                <a class="nav-link waves-effect waves-light" href="javascript:;"
                                    title="<?php echo __('messages.dashboard_menu.invoice');?>"><?php echo __('messages.dashboard_menu.invoice');?></a>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li class=""><a title="<?php echo __('messages.dashboard_menu.invoicing');?>"
                                        class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='invoicing'){{'active'}}@endif"
                                        href="{{url(Auth::user()->role.'/invoicing')}}"><?php echo __('messages.dashboard_menu.invoicing');?></a>
                                </li>
                                <li class=""><a title="<?php echo __('messages.dashboard_menu.sellerinvoices');?>"
                                        class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='sellerinvoices'){{'active'}}@endif"
                                        href="{{url(Auth::user()->role.'/sellerinvoices')}}"><?php echo __('messages.dashboard_menu.sellerinvoices');?></a>
                                </li>

                            </ul>

                        </div>
                    </li>
                    <!--Naveen-->
                    <li
                        class="nav-item @if(isset($url[1]) && $url[1]=='shopgallary' ||  $url[1]=='gallarylist' || $url[1]=='seller-info'){{'active'}}@endif">
                        <div class="dropdown">
                            <button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
                                <a class="nav-link waves-effect waves-light" href="javascript:;"
                                    title="<?php echo __('messages.dashboard_menu.invoice');?>"><?php echo __('messages.dashboard_menu.shop_gallary');?></a>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li class=""><a title="<?php echo __('messages.dashboard_menu.invoicing');?>"
                                        class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='shopgallary'){{'active'}}@endif"
                                        href="{{url(Auth::user()->role.'/shopgallary')}}"><?php echo __('messages.dashboard_menu.subshop_gallary'); ?></a>
                                </li>
                                <li class=""><a title="<?php echo __('messages.dashboard_menu.sellerinvoices');?>"
                                        class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='gallarylist'){{'active'}}@endif"
                                        href="{{url(Auth::user()->role.'/gallarylist')}}"><?php echo __('messages.dashboard_menu.gallary_list'); ?></a>
                                </li>
                                @if(!empty($shop) && isset($shop['id']))
                                <li class=""><a title="<?php echo __('messages.shop.seller_shop');?>"
                                        class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='seller-info'){{'active'}}@endif"
                                        href="{{url(Auth::user()->role.'/seller-info/'.base64_encode($shop['id']))}}"><?php echo __('messages.shop.seller_shop'); ?></a>
                                </li>
                                @if(!empty($getPakage) && $getPakage['status'] == 1 )
                                <li class=""><a title="<?php echo __('messages.shop.seller_info');?>"
                                        class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='seller-shop'){{'active'}}@endif"
                                        href="{{url(Auth::user()->role.'/seller-shop/'.base64_encode($shop['id']))}}"><?php echo __('messages.shop.seller_info'); ?></a>
                                </li>
                                @endif
                                @else
                                <li class=""><a title="<?php echo __('messages.shop.seller_shop');?>"
                                        class="waves-effect waves-light @if(isset($url[1]) && $url[1]=='seller-info'){{'active'}}@endif"
                                        href="{{url(Auth::user()->role.'/seller-info')}}"><?php echo __('messages.shop.seller_shop'); ?></a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>