<div class="bottom_header">
    <div class="container">
        <div class="logo">
            <a href="{{url('auction')}}"><img alt="logo" src="{{asset('img/logo.png')}}" /></a>
        </div>
        <div class="allcategories_search_bg">
            <form action="{{url('listing')}}" method="get" id="listing_search" class="d-flex">
                <div class="blk1 clear-fix">
                    <div class="allcategories">
                        <div class="dropdown">
                            <select class="all-cat-drop" name="category">
                                @php $categories = Helper::parent_categories() @endphp
                                <option value="">{{__('messages.head_search.all_categories')}}</option>
                                @if($categories)
                                @foreach($categories as $key => $cate)
                                <?php $length = strlen($cate);
                           if($length > 22){
                             $cat_name = substr($cate,0,22)."..."; 
                           }else{
                              $cat_name = $cate;
                           }
                           
                           ?>
                                <option value="{{$key}}"
                                    {{(Session::has('search_category') && Session::get('search_category') == $key)?'selected':''}}>
                                    {{$cat_name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <!-- <div class="list_select_box">
               <label><?php echo __('messages.filter.filter_text');?></label>  
               <?php $filter = Session::get('filter'); ?>
               <select class="form-control" name="filter" id="filter" onchange="filter_form_submit(this.form)">
                  <option value="all" <?php echo empty($filter) ? 'selected':''; ?>><?php echo __('messages.filter.all');?></option>
                  <option value="fixed" <?php echo (!empty($filter) && $filter=='fixed')? 'selected':''; ?>><?php echo __('messages.filter.fixed');?></option>
                  <option value="offer" <?php echo (!empty($filter) && $filter=='offer')? 'selected':''; ?>><?php echo __('messages.filter.offer');?></option>
                  <option value="auction" <?php echo (!empty($filter) && $filter=='auction')? 'selected':''; ?>><?php echo __('messages.filter.auction');?></option>
               </select>
               </div> -->
                <!-- value="{{Session::has('search_title')?Session::get('search_title'):Session::get('filters.title')}}" -->
                <div class="blk2 clear-fix d-flex">

                    <div class="search_fild">
                        <input type="text" class="form-control" name="title" id="title"
                            value="{{Session::has('filters.title')?Session::get('filters.title'):Session::get('filters.title')}}"
                            placeholder="{{__('messages.head_search.search')}}..." />
                    </div>
                    <div class="list_select_box">
                        <div class="btn-group">
                            <button type="button" id="btnvalue" class="btn dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <?php 
                        $product = "";
                        $serach_type = "";
                        $productvale = "";
                        //print_r(Session::get('filters.serach_type'));                   
                        switch (Session::get('filters.serach_type')) 
                          {
                              case 'product_type':
                                  $product1 = Session::get('filters.product_type');
                                  $product = __('messages.filter.'.$product1);
                                  $serach_type = "product_type";
                                  $productvale = Session::get('filters.product_type');
                                  break;
                              case 'item_number':
                                  $product = __('messages.article_nr');
                                  $serach_type = "item_number";
                                  $productvale = "";
                        
                                  break;
                              case 'seller':                              
                                  $product = __('messages.filter.seller');
                                  $serach_type = "seller";
                                  $productvale = "";
                                  break; 
                              case 'city':                              
                                  $product = __('messages.extended.city');
                                  $serach_type = "city";
                                  $productvale = "";
                                  break; 
                              case 'shop':                              
                                  $product = __('messages.extended.shop_name');
                                  $serach_type = "shop";
                                  $productvale = "";
                                  break;                
                              default:
                                  $product = __('messages.search_for');
                                  $serach_type = "";
                                  $productvale = "";
                              break;                          
                          }                        
                        ?>
                                <!-- {{$product}} -->
                            </button>
                            <input type="hidden" name="product_type" id="producttypes" value="{{$productvale}}">
                            <input type="hidden" name="serach_type" id="serach_types" value="{{$serach_type}}">
                            <div class="dropdown-menu">
                                <a class="dropdown-item articalenr"
                                    href="javascript:;"><?php echo __('messages.article_nr');?></a>
                                <a class="dropdown-item producttype" data-value="auction"
                                    href="javascript:;"><?php echo __('messages.filter.auction')?></a>
                                <a class="dropdown-item producttype" data-value="fixed"
                                    href="javascript:;"><?php echo __('messages.filter.fixed')?></a>
                                <a class="dropdown-item producttype" data-value="offer"
                                    href="javascript:;"><?php echo __('messages.filter.offer')?></a>
                                <a class="dropdown-item seller"
                                    href="javascript:;"><?php echo __('messages.filter.seller')?></a>
                                <a class="dropdown-item city"
                                    href="javascript:;"><?php echo __('messages.extended.city')?></a>
                                <a class="dropdown-item shop"
                                    href="javascript:;"><?php echo __('messages.extended.shop_name')?></a>
                            </div>
                        </div>
                    </div>
                    <div class="search_button">
                        <input type="submit" value="{{__('messages.head_search.search')}}" />
                        <!-- <input class="extended-search"  type="button" value="{{__('messages.advance_search')}}"> -->
                    </div>
                    <!-- <div class="extended">
                  <div class="container">
                      <div class="row">
                        <div class="extended-head">
                          <h4>{{__('messages.extended.search')}}</h4>
                        </div>
                        <div class="close">
                          <a  href="javascript:;"><i class="fas fa-window-close"></i></a>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <select class="form-control search-by" name="search_by">
                              <option>{{__('messages.extended.search_by')}}</option>
                              <option <?php echo Session::get('filters.search_by') == 1 ?"selected":"";?> value="1">{{__('messages.extended.city')}}</option>
                              <option <?php echo Session::get('filters.search_by') == 2?"selected":"";?> value="2">{{__('messages.extended.shop_name')}}</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <input type="text" value="<?php echo Session::get('filters.shop_name')?Session::get('filters.shop_name'):"";?>" name="shop_name" class="form-control d-none shop-name" placeholder="Enter Shop Name">
                          </div> 
                          <div class="form-group">
                            <input type="text" name="city" value="<?php echo Session::get('filters.city')?Session::get('filters.city'):"";?>" class="form-control d-none city" placeholder="Enter City Name">
                          </div> 
                          <div class="form-group">
                            <button class="btn extended-search-btn"  type="button">Submit</button>
                          </div>                         
                        </div>
                      </div>
                  </div>                 
               </div> -->
                </div>
            </form>
        </div>
    </div>
</div>
<style type="text/css">
.all-cat-drop {
    float: left;
    border: 1px solid #e6e6e6;
    color: #262626;
    font-size: 15px;
    height: 45px;
    padding: 0px 15px;
    width: 200px;
}

/*.search_fild {     width: 422px; }*/
/*.allcategories_search_bg { width: 745px; }*/
.search_fild .form-control {
    border: none;
    height: 45px;
    font-size: 15px;
    color: #042a4b;
    min-width: 300px;
}

.list_select_box {
    float: left;
}

.search_fild {
    width: 30%;
}

.bottom_header .list_select_box .form-control {
    display: block;
    width: 100%;
    height: calc(3.25rem + -1px);
    padding: 0.375rem .75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}
</style>
<script type="text/javascript">
//Search BY ProductType
$(".producttype").on('click', function() {


    $('#producttypes').val($(this).data("value"));

    $('#serach_types').val("product_type");
    // $('#btnvalue').text($(this).text());
    if ($(this).data("value") == "fixed") {
        $("#title").attr("placeholder", "{{__('messages.search_for_fixed_product')}}");
    } else if ($(this).data("value") == "offer") {
        $("#title").attr("placeholder", "{{__('messages.search_for_offer_product')}}");

    } else if ($(this).data("value") == "auction") {
        $("#title").attr("placeholder", "{{__('messages.search_for_auction_product')}}");
    }


})
//Search BY Seller
$(".seller").on('click', function() {


    $('#producttypes').val("");
    $('#serach_types').val("seller");
    // $('#btnvalue').text($(this).text())
    $("#title").attr("placeholder", "{{__('messages.search_for_seller')}}");
})

//Search BY Articale number
$(".articalenr").on('click', function() {

    $('#producttypes').val('');
    $('#serach_types').val("item_number");
    // $('#btnvalue').text($(this).text())
    $("#title").attr("placeholder", "{{__('messages.search_for_articalenr')}}");
})

//Search BY City
$(".city").on('click', function() {

    $('#producttypes').val("");
    $('#serach_types').val("city");
    // $('#btnvalue').text($(this).text())
    $("#title").attr("placeholder", "{{__('messages.search_for_city')}}");
})


//Search BY Shop
$(".shop").on('click', function() {

    $('#producttypes').val("");
    $('#serach_types').val("shop");
    // $('#btnvalue').text($(this).text())
    $("#title").attr("placeholder", "{{__('messages.search_for_shop')}}");
})



// alert("{{Session::get('filter')}}")
var filter = "{{Session::get('filter')}}";
var filter1 = "{{Session::get('filters.serach_type')}}";
if (filter != "" && filter1 == "product_type") {

    $('#producttypes').val("{{$productvale}}");
    $('#serach_types').val("product_type");
    // $('#btnvalue').text("{{$productvale}}");
} else if (filter != "") {

    $('#producttypes').val("{{Session::get('filter')}}");
    $('#serach_types').val("product_type");
    // $('#btnvalue').text("{{Session::get('filter')}}");
}
</script>
<?php //echo "<pre>";print_r(Session::get('filters'));?>