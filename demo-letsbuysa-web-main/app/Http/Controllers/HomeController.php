<?php

namespace App\Http\Controllers;

use App\Model\Aboutus;
use App\Model\Address;
use App\Model\Article_reviews;
use App\Model\Bankaccount_payment;
use App\Model\Cart;
use App\Model\Category;
use App\Model\Coupon;
use App\Model\Coupon_applied;
use App\Model\Emailtemplate;
use App\Model\Gift_voucher;
use App\Model\Gift_voucher_user;
use App\Model\Global_settings;
use App\Model\Help_articles;
use App\Model\Help_categories;
use App\Model\Help_subcategories;
use App\Model\HotToday;
use App\Model\Mostviewedproduct;
use App\Model\Notification;
use App\Model\Option;
use App\Model\Order;
use App\Model\Order_details;
use App\Model\Order_track;
use App\Model\Privacy_policy;
use App\Model\Product;
use App\Model\Product_delivery_features;
use App\Model\Product_details;
use App\Model\Product_reviews;
use App\Model\Redeem_rewards;
use App\Model\Returns;
use App\Model\Reward;
use App\Model\Shipping_delivery;
use App\Model\Subcategory;
use App\Model\Subscribe_email;
use App\Model\Terms;
use App\Model\Transaction_details;
use App\Model\Wallet;
use App\Model\Wallet_recharge_history;
use App\Model\Wishlist;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Mail;
use Redirect;
use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $banners = DB::table('banner')->select('*')->where('status', 1)->get();

        $sidebanners = DB::table('clients')->select('*')->where('status', 1)->orderby('id', 'desc')->take(2)->get();
        $offerbanners = DB::table('offer_banner')->select('*')->where('status', 1)->orderby('banner_id', 'desc')->take(6)->get();
        foreach ($offerbanners as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');

            $value->category_name = str_replace(' ', '-', $category_name);

            $subcategory_name = Subcategory::where('id', $value->subcategory_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);
        }
        $hotdeals = DB::table('hotdeals')->select('*')->where('status', 1)->orderby('id', 'desc')->take(4)->get();
        foreach ($hotdeals as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');

            $value->category_name = str_replace(' ', '-', $category_name);

            $subcategory_name = Subcategory::where('id', $value->subcategory_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);
        }

        $topbrands = DB::table('brands')->select('*')->where('status', 1)->orderby('id', 'desc')->take(10)->get();
        $products = DB::table('products')->select('*', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))->where('status', 1)->orderby('id', 'desc')->take(20)->get();
        foreach ($products as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');

            $value->category_name = str_replace(' ', '-', $category_name);

            $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);
        }

        $hottoday = DB::table('products')->select('*', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))->where('status', 1)->where('discount_available', '!=', 0)->orderByRaw('RAND()')->take(20)->get();
        foreach ($hottoday as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');

            $value->category_name = str_replace(' ', '-', $category_name);

            $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);
        }
        $hotdealproduct = DB::table('products')->select('*', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))->where('status', 1)->where('discount_available', '!=', 0)->orderBy('discount_available', 'desc')->take(20)->get();
        foreach ($hotdealproduct as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');

            $value->category_name = str_replace(' ', '-', $category_name);

            $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);
        }

        $bestsellingproducts = DB::table('order_details')
            ->select('order_details.product_id', 'products.*', DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as sum'), DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))
            ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
            ->orderby('sum', 'desc')
            ->where('products.status', 1)
            ->groupby('order_details.product_id')
            ->get();
        foreach ($bestsellingproducts as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');

            $value->category_name = str_replace(' ', '-', $category_name);

            $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);
        }
        $trending = DB::table('most_product_viewed')
            ->select('most_product_viewed.product_id', 'products.id', 'products.status', 'products.name_en', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'products.stock_availabity', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))
            ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
            ->orderby('most_product_viewed.count', 'desc')
            ->whereNull('user_id')
            ->where('products.status', 1)
            ->groupby('most_product_viewed.product_id')
            ->take(20)->get();
        foreach ($trending as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');

            $value->category_name = str_replace(' ', '-', $category_name);

            $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);
        }
        $hottodaybanner = HotToday::where('status', 1)->take(4)->get();

        foreach ($hottodaybanner as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');

            $value->category_name = str_replace(' ', '-', $category_name);
            $subcategory_name = Subcategory::where('id', $value->subcategory_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);
        }

        return view('frontend.index', compact('banners', 'sidebanners', 'offerbanners', 'hotdeals', 'topbrands', 'products', 'hottoday', 'hottodaybanner', 'hotdealproduct', 'bestsellingproducts', 'trending'));
    }

    public function aboutus()
    {
        $aboutus = Aboutus::select('*')->orderby('id', 'desc')->first();
        return view('frontend.about-us', compact('aboutus'));
    }

    public function myrewards(Request $request)
    {
        $reward = Reward::where('user_id', Auth::id())->first();

        $rewardredeem = Redeem_rewards::where('user_id', Auth::id())->orderby('id', 'desc')->paginate(10);

        if (!empty($reward)) {
            $reward = $reward->reward;
        } else {
            $reward = 0;
        }
        return view('frontend.my-reward', compact('reward', 'rewardredeem'));
    }

    public function mygifts(Request $request)
    {
        $gift_transactions = Gift_voucher_user::where('sender_id', Auth::id())->paginate(10);
        return view('frontend.my-gift', compact('gift_transactions'));
    }

    public function help_center()
    {
        $category = Help_categories::select('*', DB::raw('(select count(id) from help_articles where category_id=help_categories.id)as articlecount'))->orderby('id', 'desc')->get();
        return view('frontend.help-center', compact('category'));
    }
    public function helpsubcategory(Request $request, $id)
    {
        $subcategory = Help_subcategories::with(["helpsubcategories" => function ($q) use ($id) {
            $q->where('help_articles.category_id', '=', $id);
        }])->get();
        $category = Help_categories::select('*')->where('id', $id)->orderby('id', 'desc')->first();
        $articlecount = Help_articles::select('*')->where('category_id', $id)->orderby('id', 'desc')->count();

        return view('frontend.help-center-detail', compact('subcategory', 'category', 'articlecount'));
    }

    public function search_article_category(Request $request)
    {
        if ((\App::getLocale() == 'en')) {
            $category = Help_categories::select('*', DB::raw('(select count(id) from help_articles where category_id=help_categories.id)as articlecount'))->orderby('id', 'desc')->where('name_en', 'LIKE', "%{$request->name}%")->get();
        } else {
            $category = Help_categories::select('*', DB::raw('(select count(id) from help_articles where category_id=help_categories.id)as articlecount'))->orderby('id', 'desc')->where('name_ar', 'LIKE', "%{$request->name}%")->get();
        }
        return view('frontend.help-center', compact('category'));
    }

    public function search_article(Request $request)
    {

    }

    public function helparticle(Request $request, $id, $id2)
    {
        $data = Help_articles::select('*', DB::raw('(select name_en from help_categories where help_categories.id=help_articles.category_id)as categoryname'))->where('id', $id)->orderby('id', 'desc')->first();
        $all_articles = Help_articles::select('*')->where('subcategory_id', $id2)->orderby('id', 'desc')->get();

        return view('frontend.help-center-detail-info', compact('data', 'all_articles'));
    }

    public function article_review(Request $request, $id, $id2)
    {
        $article = new Article_reviews;
        $article->article_id = $id;
        $article->review_status = $id2;
        $article->save();

        if ((\App::getLocale() == 'en')) {
            toastr()->success('Thank you For Your Opinion');
        } else {
            toastr()->success('شكرا لرأيك');
        }

        return redirect(url()->previous());
    }

    public function addaddress(Request $request)
    {

        if ($request->isMethod('post')) {

            $rules = [
                'name' => 'required|max:255',
                'mobile' => 'required|max:15',
                'fulladdress' => 'required',
            ];

            $messages = [
                'name.required' => 'Please Enter Name',
                'name.max' => 'Name Must be less than 255 characters',

                'mobile.required' => 'Please Enter Mobile Number',
                'mobile.max' => 'Mobile Number Must be less than 15 characters',

                'fulladdress.required' => 'Please Select Address From Map',

            ];

            $v = Validator::make($request->all(), $rules, $messages);
            if ($v->fails()) {
                return redirect()->back()->withInput()->withErrors($v);
            }

            // if($request->is_default == 1)
            // {
            //     Address::where('user_id',Auth::id())->update(['is_default'=>0]);
            // }

            $address = new Address;
            $address->user_id = Auth::id();
            $address->fulladdress = $request->fulladdress;
            $address->fullname = $request->name;
            $address->mobile = $request->mobile;
            $address->address_details = $request->address_details;
            $address->lat = $request->lat;
            $address->long = $request->long;
            $address->save();

            if ((\App::getLocale() == 'en')) {
                toastr()->success('Address Added Successfully');
            } else {
                toastr()->success('تمت إضافة العنوان بنجاح');
            }
            if ($request->has('samepage') && $request->samepage == 0) {
                return redirect('myaddress');
            } else {
                return \Redirect::route('checkoutpayment', ['address_id' => base64_encode($address->id)]);
            }
        }

        $country = DB::table('country')->select('*')->where('status', 1)->get();
        return view('frontend.add-address', compact('country'));
    }

    public function myaddress()
    {
        $user_address = Address::where('user_id', Auth::id())->get();

        if (count($user_address) > 0) {
            return view('frontend.save-address', compact('user_address'));
        }
        return redirect('noaddress');

    }

    public function terms()
    {
        $terms = Terms::select('*')->orderby('id', 'desc')->first();
        return view('frontend.terms-of-use', compact('terms'));
    }

    public function privacy()
    {
        $privacy = Privacy_policy::select('*')->orderby('id', 'desc')->first();
        return view('frontend.privacy', compact('privacy'));
    }

    public function returns()
    {
        $return = Returns::select('*')->orderby('id', 'desc')->first();
        return view('frontend.return', compact('return'));
    }

    public function bank_account_payment()
    {
        $bankaccount = Bankaccount_payment::select('*')->orderby('id', 'desc')->first();
        return view('frontend.bank-account', compact('bankaccount'));
    }

    public function shipping_delivery()
    {
        $shipping = Shipping_delivery::select('*')->orderby('id', 'desc')->first();
        return view('frontend.shipping-delivery', compact('shipping'));
    }

    public function wishlist()
    {
        $products = DB::table('wishlist')
            ->select('products.*')
            ->join('products', 'products.id', '=', 'wishlist.product_id')
            ->where('wishlist.user_id', Auth::id())
            ->orderby('wishlist.id', 'desc')
            ->get();
        foreach ($products as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');

            $value->category_name = str_replace(' ', '-', $category_name);

            $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);
        }
        return view('frontend.my-wishlist', compact('products'));
    }

    public function addtocart(Request $request)
    {
        $id = $request->id;
        $product = Session::get('product');
        // unset($product[$request->id]);
        $products = DB::table('products')
            ->select(
                'products.id',
                'products.name_en as product_name',
                'products.price',
                'products.category_id',
                'products.sub_category_id',
                'category.category_name_en',
                'sub_category.sub_category_name_en'
            )
            ->leftJoin('category', 'products.category_id', '=', 'category.id')
            ->leftJoin('sub_category', 'products.sub_category_id', '=', 'sub_category.id')
            ->where('products.id', $id)
            ->first();

        //dd($products);
        // $request->session()->flash('remove');
        if (!empty($product)) {
            if (array_key_exists($id, $product)) {
                if ((\App::getLocale() == 'en')) {
                    return response()->json(['error' => 'Product Alredy Available In Cart', 'status' => '2']);
                } else {
                    return response()->json(['error' => 'منتج الردي متوفر في سلة التسوق', 'status' => '2']);
                }
            } else {

                $product[$id]['nocolor'] = array(
                    "id" => $id,
                    "qty" => 1,
                );

                Session::put('product', $product);
                if ((\App::getLocale() == 'en')) {
                    return response()->json(['success' => 'Product Added to Cart', 'status' => '1', 'product' => $products]);
                } else {
                    return response()->json(['success' => 'تمت إضافة المنتج إلى عربة التسوق', 'status' => '1', 'product' => $products]);
                }
            }
        }

        if (empty($product)) {
            $product[$id]['nocolor'] = array(
                "id" => $id,
                "qty" => 1,
            );

            Session::put('product', $product);
            if ((\App::getLocale() == 'en')) {
                return response()->json(['success' => 'Product Added to Cart', 'status' => '1', 'product' => $products]);
            } else {
                return response()->json(['success' => 'تمت إضافة المنتج إلى عربة التسوق', 'status' => '1', 'product' => $products]);
            }
        }

    }

    public function productlist($id1, $id2)
    {
        $id2 = base64_decode($id2);
        $products = DB::table('products')->select('*', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))->where('status', 1)->where('category_id', base64_decode($id1))->whereRaw('FIND_IN_SET(' . $id2 . ',sub_category_id)')->orderby('id', 'desc')->paginate(52);
        foreach ($products as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');
            $value->category_name = str_replace(' ', '-', $category_name);
            $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);

        }

        $categoryid = base64_decode($id1);
        $subcategoryid = $id2;

        $subcategory = Subcategory::where('id', $id2)->first();

        if ((\App::getLocale() == 'en')) {
            $subcat_name = $subcategory->sub_category_name_en;
        } else {
            $subcat_name = $subcategory->sub_category_name_ar;
        }

        $maxprice = 3000;
        $minprice = 0;

        return view('frontend.product-list', compact('products', 'maxprice', 'minprice', 'subcat_name', 'categoryid', 'subcategoryid'));
    }

    public function noaddress()
    {
        return view('frontend.no-address');
    }

    public function checkoutaddress(Request $request)
    {
        $address = Address::where('user_id', Auth::id())->get();
        $country = DB::table('country')->select('*')->where('status', 1)->get();
        return view('frontend.checkout-address', compact('address', 'country'));
    }

    public function checkoutpayment(Request $request)
    {

        if (!is_numeric(base64_decode($request->address_id))) {
            return redirect(url()->previous());
        }

        if (!empty($request->address_id)) {
            Session::put('address_id', base64_decode($request->address_id));
        }

        $address = Address::where('id', Session::get('address_id'))->first();

        $product = Session::get('product');

        $products = DB::table('products')->select('*')->where('status', 1)->whereIn('id', array_keys($product))->orderby('id', 'desc')->get();
        $order_price = 0;
        $productdata = array();
        foreach ($products as $productnew) {
            foreach ($product[$productnew->id] as $key => $colorpro) {
                if ($colorpro['id'] == $productnew->id) {
                    $productdata[$productnew->id][$key]['req_quantity'] = $colorpro['qty'];
                    $productdata[$productnew->id][$key]['quantity'] = $productnew->quantity;
                    $productdata[$productnew->id][$key]['id'] = $productnew->id;
                    $productdata[$productnew->id][$key]['name_en'] = $productnew->name_en;
                    $productdata[$productnew->id][$key]['name_ar'] = $productnew->name_ar;
                    $productdata[$productnew->id][$key]['img'] = $productnew->img;
                    if ($key != 'nocolor') {
                        $product_detail_option = explode(',', $key);
                        $products1 = DB::table('product_details')->select('*')->where('status', 1)->where('id', $product_detail_option[0])->first();
                        $productdata[$productnew->id][$key]['price'] = $productnew->price + $products1->price;
                    } else {
                        $productdata[$productnew->id][$key]['price'] = $productnew->price;
                    }
                    $productdata[$productnew->id][$key]['offer_price'] = $productnew->offer_price;
                    $productdata[$productnew->id][$key]['discount_available'] = $productnew->discount_available;

                }
                $order_price = $order_price + $productdata[$productnew->id][$key]['req_quantity'] * $productdata[$productnew->id][$key]['price'];
            }
        }
        $global = Global_settings::all();
        $ship_charge = 0;

        if (!empty($global[0]->shipping_charge) && !empty($global[0]->min_amount_shipping)) {
            if ($order_price >= $global[0]->min_amount_shipping) {
                $ship_charge = 0;
            } else {
                $ship_charge = $global[0]->shipping_charge;
            }
        }

        $order_price_subtotal = $order_price;

        $order_price = $order_price + $ship_charge;
        $delivery_charge = $global[0]->delivery_charge;

        $wallet = Wallet::where('user_id', Auth::id())->first();
        $entityId = "8ac9a4c97dbd4ae3017dc31689fc62e3";
        $types = " MADA";
        //-------deepak work start-------------//
        $url = "https://eu-prod.oppwa.com/v1/checkouts";
        $data = "entityId=" . $entityId .
            "&amount=" . $order_price .
            "&currency=SAR" .
            "&paymentType=DB";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjOWE0Yzk3ZGJkNGFlMzAxN2RjMzE1N2NlNzYyY2Z8S3lSZTlZaHJqRA=='));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        // dd($responseData);
        $data = 1;
        $address_id = $request->address_id;
        $global_settings = DB::table('global_settings')->select('*')->first();
        $codCountries = DB::table('cod_countries')->where('global_id', $global_settings->id)->pluck('COD_Country_Name')->toArray();
        $addressCodExist = false;
        foreach ($codCountries as $key => $value) {
            $addressCodcheckExist = Address::where('id', Session::get('address_id'))->where('fulladdress', 'LIKE', "%{$value}%")->first();
            if ($addressCodcheckExist != null) {
                $addressCodExist = true;
            }
        }

        return view('frontend.checkout-payment', compact('addressCodExist', 'entityId', 'types', 'data', 'address', 'address_id', 'order_price', 'ship_charge', 'delivery_charge', 'order_price_subtotal', 'wallet'));
    }

    public function myaccount(Request $request)
    {
        if ($request->isMethod('post')) {
            $rules = [
                'name' => 'required',
                'email' => 'required|email',
                'mobile' => 'required',
            ];

            $messages = [
                'name.required' => 'Please Enter Name',
                'email.required' => 'Please Enter Email',
                'mobile.required' => 'Please Enter Mobile',
            ];

            $v = Validator::make($request->all(), $rules, $messages);
            if ($v->fails()) {
                foreach ($request->all() as $key => $value) {
                    if ($v->errors()->first($key)) {
                        return redirect()->back()->with('error', $v->errors()->first($key))->withInput();
                    }
                }
            }

            $user = User::where('id', Auth::id())->update(['mobile' => $request->mobile, 'name' => $request->name]);
            if ((\App::getLocale() == 'en')) {
                toastr()->success('Account Updated Successfully');
            } else {
                toastr()->success('تم تحديث الحساب بنجاح');
            }
            return redirect(url()->previous());
        } else {
            $user = User::where('id', Auth::id())->first();
            return view('frontend.my-account', compact('user'));
        }
    }

    public function myorder(Request $request)
    {
        $myorders = DB::table('order')->select('*', DB::raw('(select status_name_en from order_status where order_status.id=order.status)as statusname'), DB::raw('(select count(id) from order_details where order_id=order.id)as itemcount'))->where('user_id', Auth::id())->orderby('id', 'desc')->paginate(5);

        $myorderscount = DB::table('order')->select('*', DB::raw('(select status_name_en from order_status where order_status.id=order.status)as statusname'), DB::raw('(select count(id) from order_details where order_id=order.id)as itemcount'))->where('user_id', Auth::id())->orderby('id', 'desc')->count();

        return view('frontend.my-orders', compact('myorders', 'myorderscount'));
    }

    public function mywallet(Request $request)
    {
        $wallet = Wallet::where('user_id', Auth::id())->first();

        $walletrecharge = Wallet_recharge_history::where('user_id', Auth::id())->orderby('id', 'desc')->paginate(10);

        if (!empty($wallet)) {
            $amount = $wallet->amount;
        } else {
            $amount = 0;
        }
        return view('frontend.my-wallet', compact('amount', 'walletrecharge'));
    }

    public function productdetails($id, $id2, $id3)
    {
        $id = $id3;
        $productid = DB::table('products')->select('id')->where('seo_url', $id)->first();

        if (!empty($productid)) {
            $id = $productid->id;
        } else {
            return redirect(url()->previous());
        }

        $product = DB::table('products')->select('*', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))->where('id', $id)->where('status', 1)->orderby('id', 'desc')->first();

        if ($product != null) {
            $category_name = Category::where('id', $product->category_id)->value('category_name_en');

            $product->category_name = str_replace(' ', '-', $category_name);

            $subcategory_name = Subcategory::where('id', $product->sub_category_id)->value('sub_category_name_en');
            $product->sub_category_name = str_replace(' ', '-', $subcategory_name);
            if ((\App::getLocale() == 'en')) {
                $product_name = $product->name_en;
            } else {
                $product_name = $product->name_ar;
            }
            $deliveryfeatures = Product_delivery_features::whereIN('id', explode(',', $product->delivery_features))->get();
            $related = $product->relatedproducts;

        } else {
            return redirect()->back()->with('error', "this product does not exist");
        }

        $all_similar_products = array();

        if (!empty($related)) {
            $all_similar_products = DB::table('products')->select('*', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))->whereIN('id', explode(',', $related))->where('status', 1)->orderby('id', 'desc')->get();
            foreach ($all_similar_products as $key => $value) {
                $category_name = Category::where('id', $value->category_id)->value('category_name_en');
                $value->category_name = str_replace(' ', '-', $category_name);
                $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
                $value->sub_category_name = str_replace(' ', '-', $subcategory_name);
            }
        }

        $all_recently_products = DB::table('most_product_viewed')
            ->select('most_product_viewed.product_id', 'sub_category.sub_category_name_en as sub_category_name', 'category.category_name_en as category_name', 'products.id', 'products.name_en', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'products.stock_availabity', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))
            ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
            ->leftjoin('category', 'products.category_id', '=', 'category.id')
            ->leftjoin('sub_category', 'products.sub_category_id', '=', 'sub_category.id')
            ->orderby('most_product_viewed.id', 'desc')
            ->where('user_id', Auth::id())
            ->groupby('most_product_viewed.product_id')
            ->take(10)->get();

        // $product_detail = DB::select('select * from `product_details` where `product_id` = '.$id.' group by `color`');
        $product_detail = Product_details::select('*')->where('product_id', $id)->get();

        $mydata = [];
        foreach ($product_detail as $myproductdetail) {
            $data['id'] = $myproductdetail->id;
            $data['product_id'] = $myproductdetail->product_id;
            $data['color'] = $myproductdetail->color;
            $data['price'] = $myproductdetail->price;
            $data['quantity'] = $myproductdetail->quantity;
            $data['image'] = $myproductdetail->image;
            $data['status'] = $myproductdetail->status;
            $data['option_id'] = $myproductdetail->option_id;
            $data['option_value'] = $myproductdetail->option_value;

            if (count($mydata) > 0) {

                if (array_search($myproductdetail->color, array_column($mydata, 'color')) === false) {
                    array_push($mydata, $data);
                }
            } else {
                array_push($mydata, $data);
            }

        }
        $product_detail = $mydata;

        $product_detail_color = Product_details::select('*')->where('product_id', $id)->wherenull('color')->get();
        $product_options = Product_details::select('*')->where('product_id', $id)->get();
        $product_attribute = DB::table('product_attribute')->select('*', DB::raw('(select name_en from attributes where attributes.id=product_attribute.attribute_id)as attributename_en'), DB::raw('(select name_ar from attributes where attributes.id=product_attribute.attribute_id)as attributename_ar'))->where('product_id', $id)->get();

        $options = Option::with(["productoptions" => function ($q) use ($id) {
            $q->where('product_details.product_id', '=', $id);
        }])->get();

        $product_reviews = Product_reviews::where('product_id', $id)->where('approved', 1)->get();
        $product_reviews_count = Product_reviews::where('product_id', $id)->where('approved', 1)->count();

        $mostdata = Mostviewedproduct::where('product_id', $id)->where('user_id', Auth::id())->first();

        if (empty($mostdata)) {
            if (!empty(Auth::id())) {
                $viewed = new Mostviewedproduct;
                $viewed->product_id = $id;
                $viewed->user_id = Auth::id();
                $viewed->save();
            } else {
                $viewed = new Mostviewedproduct;
                $viewed->product_id = $id;
                $viewed->save();
            }
        } else {
            $mostdata = Mostviewedproduct::where('product_id', $id)->whereNull('user_id')->first();

            if (!empty($mostdata)) {
                Mostviewedproduct::where('product_id', $id)->whereNull('user_id')->update(['count' => $mostdata->count + 1]);
            }
        }

        return view('frontend.product-details', compact('product_name', 'product', 'all_similar_products', 'all_recently_products', 'deliveryfeatures', 'product_reviews', 'product_reviews_count', 'product_detail', 'options', 'product_attribute', 'product_detail_color'));
    }

    public function productsearch(Request $request)
    {
        $product_name = $request->search;
        if (empty($request->page)) {
            Session::put('productsearchdata', $request->search);
        }
        $searchvalue = Session::get('productsearchdata');

        if ((\App::getLocale() == 'en')) {
            $products = DB::table('products')->select('*', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'),
                DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))
                ->where(function ($query) use ($searchvalue) {
                    $query->where('name_en', 'LIKE', '%' . $searchvalue . '%')
                        ->orWhere('sku_en', 'LIKE', '%' . $searchvalue . '%');
                })
                ->where('status', '1')->orderby('id', 'desc')->where('status', 1)->paginate(52);
        } else {
            $products = DB::table('products')->select('*', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'),
                DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))
                ->where(function ($query) use ($searchvalue) {
                    $query->where('name_ar', 'LIKE', '%' . $searchvalue . '%')
                        ->orWhere('sku_ar', 'LIKE', '%' . $searchvalue . '%');
                })
                ->where('status', '1')->orderby('id', 'desc')->where('status', 1)->paginate(52);
        }

        foreach ($products as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');
            $value->category_name = str_replace(' ', '-', $category_name);
            $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);

        }
        $maxprice = 3000;
        $minprice = 0;

        if (count($products) == 0) {
            return view('frontend.no-product', compact('product_name'));
        }

        return view('frontend.product-list', compact('products', 'product_name', 'maxprice', 'minprice'));
    }

    public function noproduct()
    {
        return view('frontend.no-product');
    }

    public function addwishlist(Request $request)
    {
        $id = $request->id;
        $product_data = Product::where('id', $id)->first();
        $product_info = DB::table('products')
            ->select(
                'products.id',
                'products.name_en as product_name',
                'products.price',
                'products.category_id',
                'products.sub_category_id',
                'category.category_name_en',
                'sub_category.sub_category_name_en'
            )
            ->leftJoin('category', 'products.category_id', '=', 'category.id')
            ->leftJoin('sub_category', 'products.sub_category_id', '=', 'sub_category.id')
            ->where('products.id', $id)
            ->first();
        $subcat = explode(',', $product_data->sub_category_id);
        if (!empty($product_data)) {
            $wishlistdata = Wishlist::where('user_id', Auth::id())->where('product_id', $id)->first();
            if (empty($wishlistdata)) {
                $wishlist = new Wishlist;
                $wishlist->user_id = Auth::id();
                $wishlist->product_id = $product_data->id;
                $wishlist->price = $product_data->offer_price;
                $wishlist->category_id = $product_data->category_id;
                $wishlist->subcategory_id = $subcat[0];
                $wishlist->save();
                if ((\App::getLocale() == 'en')) {
                    return response()->json(['success' => 'Product Added to WishList', 'status' => '1', 'product' => $product_info]);
                } else {
                    return response()->json(['success' => 'تمت إضافة المنتج إلى قائمة الرغبات', 'status' => '1', 'product' => $product_info]);
                }

            }
            if ((\App::getLocale() == 'en')) {
                return response()->json(['error' => 'Product Alreday Available In Wishlist', 'status' => '2']);
            } else {
                return response()->json(['error' => 'المنتج Alreday متوفر في قائمة الرغبات', 'status' => '2']);
            }

        }
        return redirect('/');
    }

    public function removewishlist(Request $request, $id)
    {
        $id = $request->id;
        $product_data = Product::where('id', $id)->first();

        if (!empty($product_data)) {
            $wishlistdata = Wishlist::where('user_id', Auth::id())->where('product_id', $id)->delete();

            if ((\App::getLocale() == 'en')) {
                $message = 'Product Removed From Wishlist Successfully';
            } else {
                $message = 'تمت إزالة المنتج من قائمة الرغبات بنجاح';
            }
            return response()->json(['url' => url()->previous(), 'message' => $message], 200);

        }
        return response()->json(['url' => url()->previous()], 200);
    }

    public function address_edit(Request $request, $id)
    {
        if ($request->isMethod('post')) {

            $rules = [
                'name' => 'required|max:255',
                'mobile' => 'required|max:15',
            ];

            $messages = [
                'name.required' => 'Please Enter Name',
                'name.max' => 'Name Must be less than 255 characters',

                'mobile.required' => 'Please Enter Mobile Number',
                'mobile.max' => 'Mobile Number Must be less than 15 characters',

            ];

            $v = Validator::make($request->all(), $rules, $messages);
            if ($v->fails()) {
                return redirect()->back()->withInput()->withErrors($v);
            }

            $address = Address::find(base64_decode($id));
            $address->fulladdress = $request->fulladdress;
            $address->fullname = $request->name;
            $address->mobile = $request->mobile;
            $address->address_details = $request->address_details;
            $address->lat = $request->lat;
            $address->long = $request->long;
            $address->save();

            if ((\App::getLocale() == 'en')) {
                toastr()->success('Address Updated Successfully');
            } else {
                toastr()->success('تم تحديث العنوان بنجاح');
            }
            return redirect('myaddress');

        }

        $address = Address::where('id', base64_decode($id))->first();
        $country = DB::table('country')->select('*')->where('status', 1)->get();

        return view('frontend.edit-address', compact('address', 'country'));

    }

    public function address_delete(Request $request, $id)
    {
        Address::where('id', base64_decode($id))->delete();
        $address = Address::where('user_id', Auth::id())->get();

        if ((\App::getLocale() == 'en')) {
            toastr()->success('Address Deleted Successfully');
        } else {
            toastr()->success('تم حذف العنوان بنجاح');
        }
        if (count($address) > 0) {
            return redirect('/myaddress');
        } else {
            return redirect('/noaddress');
        }
    }

    public function removecart(Request $request)
    {
        $product = Session::get('product');

        unset($product[$request->id][$request->color]);
        Session::put('product', $product);

        if ((\App::getLocale() == 'en')) {
            $message = 'Product Removed Successfully';
        } else {
            $message = 'تمت إزالة المنتج بنجاح';
        }

        $request->session()->flash('remove');
        return response()->json(['url' => url()->previous(), 'message' => $message], 200);

    }

    public function removecartvalue(Request $request, $id)
    {

        $del_val = base64_decode($id);
        $product = Session::get('product');

        unset($product[$del_val]);
        Session::put('product', $product);

        if ((\App::getLocale() == 'en')) {
            toastr()->success('Product Removed Successfully');
        } else {
            toastr()->success('تمت إزالة المنتج بنجاح');
        }

        return redirect(url()->previous());

    }

    public function product_quantity_increase(Request $request)
    {
        $id = $request->id;
        $color = $request->color;

        $product = Session::get('product');
        $products1 = DB::table('products')->select('*')->where('status', 1)->whereIn('id', array_keys($product))->orderby('id', 'desc')->get();

        $products = DB::table('products')->select('*', DB::raw('(select quantity from product_details where product_details.product_id=products.id AND product_details.color="' . $color . '")as quantity'))->where('status', 1)->whereIn('id', array_keys($product))->orderby('id', 'desc')->get();

        $productdata1 = array();

        foreach ($products as $firstkey => $productnew1) {
            foreach ($product[$productnew1->id] as $key => $colorpro) {
                if ($colorpro['id'] == $productnew1->id) {
                    $productdata1[$productnew1->id][$key]['req_quantity'] = $colorpro['qty'];
                    $productdata1[$productnew1->id][$key]['color'] = $key;
                    if (!empty($productnew1->quantity)) {
                        $productdata1[$productnew1->id][$key]['quantity'] = $productnew1->quantity;
                    } else {
                        $productdata1[$productnew1->id][$key]['quantity'] = $products1[$firstkey]->quantity;
                    }
                    if ($key != 'nocolor') {
                        $product_detail_option = explode(',', $key);
                        $products2 = DB::table('product_details')->select('*')->where('status', 1)->where('product_id', $productnew1->id)->where('id', $product_detail_option[0])->first();

                        $productdata1[$productnew1->id][$key]['price'] = $productnew1->price + $products2->price;
                        $productdata1[$productnew1->id][$key]['quantity'] = $products2->quantity;
                    } else {
                        $productdata1[$productnew1->id][$key]['price'] = $productnew1->price;
                    }
                    $productdata1[$productnew1->id][$key]['id'] = $productnew1->id;
                    $productdata1[$productnew1->id][$key]['name_en'] = $productnew1->name_en;
                    $productdata1[$productnew1->id][$key]['name_ar'] = $productnew1->name_ar;
                    $productdata1[$productnew1->id][$key]['img'] = $productnew1->img;

                    $productdata1[$productnew1->id][$key]['offer_price'] = $productnew1->offer_price;
                    $productdata1[$productnew1->id][$key]['discount_available'] = $productnew1->discount_available;
                }
            }
        }

        if ($productdata1[$id][$color]['req_quantity'] == $productdata1[$id][$color]['quantity']) {
            if ((\App::getLocale() == 'en')) {
                return response()->json(['success' => 'Product Quantity Cannot be Greater than Available Quantity', 'status' => '2']);
            } else {
                return response()->json(['success' => 'لا يمكن أن تكون كمية المنتج أكبر من الكمية المتوفرة', 'status' => '2']);
            }
        }

        $product[$id][$color]['qty'] = $product[$id][$color]['qty'] + 1;
        Session::put('product', $product);
        $product = Session::get('product');

        $order_price = 0;
        $productdata = array();
        foreach ($products as $productnew) {
            foreach ($product[$productnew->id] as $key => $colorpro) {
                if ($colorpro['id'] == $productnew->id) {
                    $productdata[$productnew->id][$key]['req_quantity'] = $colorpro['qty'];
                    $productdata[$productnew->id][$key]['color'] = $key;
                    $productdata[$productnew->id][$key]['quantity'] = $productnew->quantity;
                    $productdata[$productnew->id][$key]['id'] = $productnew->id;
                    $productdata[$productnew->id][$key]['name_en'] = $productnew->name_en;
                    $productdata[$productnew->id][$key]['name_ar'] = $productnew->name_ar;
                    $productdata[$productnew->id][$key]['img'] = $productnew->img;
                    if ($key != 'nocolor') {
                        $product_detail_option = explode(',', $key);
                        $products2 = DB::table('product_details')->select('*')->where('status', 1)->where('product_id', $productnew->id)->where('id', $product_detail_option[0])->first();

                        $productdata[$productnew->id][$key]['price'] = $productnew->price + $products2->price;
                    } else {
                        $productdata[$productnew->id][$key]['price'] = $productnew->price;
                    }
                    $productdata[$productnew->id][$key]['offer_price'] = $productnew->offer_price;
                    $productdata[$productnew->id][$key]['discount_available'] = $productnew->discount_available;
                }
                $order_price = $order_price + $productdata[$productnew->id][$key]['req_quantity'] * $productdata[$productnew->id][$key]['price'];
            }
        }
        $global = Global_settings::all();

        $add_shipping = $global[0]->min_amount_shipping - $order_price;
        if ((\App::getLocale() == 'en')) {
            if ($add_shipping > 0) {
                $ship = '<b>' . "Add  <span style =color:green;>SR $add_shipping</span> To Get <span style =color:green;>Free Shipping</span>";
            } else {
                $ship = '<b>' . "You Are Eligible to Free Shipping";
            }
        } else {

            if ($add_shipping > 0) {
                $ship = '<b>' . "اضف  <span style =color:green;>ريال $add_shipping</span> لتحصل على <span style =color:green;>شحن مجاني</span>";
            } else {
                $ship = '<b>' . "أنت مؤهل للشحن المجاني";
            }
        }

        $ship_charge = 0;
        $delivery_charge = 0;

        if (!empty($global[0]->shipping_charge) && !empty($global[0]->min_amount_shipping)) {
            if ($order_price >= $global[0]->min_amount_shipping) {
                $ship_charge = 0;
            } else {
                $ship_charge = $global[0]->shipping_charge;
            }
        }

        if ($request->payment == 2) {
            $delivery_charge = $global[0]->delivery_charge;
        }
        $order_price_subtotal = $order_price;

        $order_price = $order_price + $ship_charge + $delivery_charge;
        Session::forget('coupan_code');

        $product = Session::get('product');
        $producttotalcountvalue = 0;
        if (!empty($product)) {
            foreach ($product as $record) {
                foreach ($record as $nocolors) {
                    $producttotalcountvalue = $producttotalcountvalue + $nocolors['qty'];
                }
            }
        }

        if ((\App::getLocale() == 'en')) {
            return response()->json(['success' => 'Product Quantity Updated', 'status' => '1', 'order_price' => $order_price, 'ship_charge' => $ship_charge, 'delivery_charge' => $delivery_charge, 'order_price_subtotal' => $order_price_subtotal, 'ship' => $ship, 'producttotalcountvalue' => $producttotalcountvalue]);
        } else {
            return response()->json(['success' => 'تم تحديث كمية المنتج', 'status' => '1', 'order_price' => $order_price, 'ship_charge' => $ship_charge, 'delivery_charge' => $delivery_charge, 'order_price_subtotal' => $order_price_subtotal, 'ship' => $ship, 'producttotalcountvalue' => $producttotalcountvalue]);
        }

    }

    public function product_quantity_decrease(Request $request)
    {
        $id = $request->id;
        $color = $request->color;

        $product = Session::get('product');
        if ($product[$id][$color]['qty'] == 1) {
            if ((\App::getLocale() == 'en')) {
                return response()->json(['success' => 'Product Quantity Cannot be zero', 'status' => '2']);
            } else {
                return response()->json(['success' => 'لا يمكن أن تكون كمية المنتج صفرًا', 'status' => '2']);
            }
        }
        $product[$id][$color]['qty'] = $product[$id][$color]['qty'] - 1;
        Session::put('product', $product);
        $product = Session::get('product');

        $producttotalcountvalue = 0;
        if (!empty($product)) {
            foreach ($product as $record) {
                foreach ($record as $nocolors) {
                    $producttotalcountvalue = $producttotalcountvalue + $nocolors['qty'];
                }
            }
        }

        $products1 = DB::table('products')->select('*')->where('status', 1)->whereIn('id', array_keys($product))->orderby('id', 'desc')->get();

        $products = DB::table('products')->select('*', DB::raw('(select quantity from product_details where product_details.product_id=products.id AND product_details.color="' . $color . '")as quantity'))->where('status', 1)->whereIn('id', array_keys($product))->orderby('id', 'desc')->get();

        $order_price = 0;
        $productdata = array();
        foreach ($products as $firstkey => $productnew) {
            foreach ($product[$productnew->id] as $key => $colorpro) {
                if ($colorpro['id'] == $productnew->id) {
                    $productdata[$productnew->id][$key]['req_quantity'] = $colorpro['qty'];
                    $productdata[$productnew->id][$key]['color'] = $key;
                    if (!empty($productnew->quantity)) {
                        $productdata[$productnew->id][$key]['quantity'] = $productnew->quantity;
                    } else {
                        $productdata[$productnew->id][$key]['quantity'] = $products1[$firstkey]->quantity;
                    }
                    if ($key != 'nocolor') {
                        $product_detail_option = explode(',', $key);
                        $products2 = DB::table('product_details')->select('*')->where('status', 1)->where('product_id', $productnew->id)->where('id', $product_detail_option[0])->first();

                        $productdata[$productnew->id][$key]['price'] = $productnew->price + $products2->price;
                    } else {
                        $productdata[$productnew->id][$key]['price'] = $productnew->price;
                    }
                    $productdata[$productnew->id][$key]['id'] = $productnew->id;
                    $productdata[$productnew->id][$key]['name_en'] = $productnew->name_en;
                    $productdata[$productnew->id][$key]['name_ar'] = $productnew->name_ar;
                    $productdata[$productnew->id][$key]['img'] = $productnew->img;
                    $productdata[$productnew->id][$key]['offer_price'] = $productnew->offer_price;
                    $productdata[$productnew->id][$key]['discount_available'] = $productnew->discount_available;
                }
                $order_price = $order_price + $productdata[$productnew->id][$key]['req_quantity'] * $productdata[$productnew->id][$key]['price'];

            }
        }

        $global = Global_settings::all();
        $add_shipping = $global[0]->min_amount_shipping - $order_price;

        if ((\App::getLocale() == 'en')) {
            if ($add_shipping > 0) {
                $ship = '<b>' . "Add  <span style =color:green;>SR $add_shipping</span> To Get <span style =color:green;>Free Shipping</span>";
            } else {
                $ship = '<b>' . "You Are Eligible to Free Shipping";
            }
        } else {

            if ($add_shipping > 0) {
                $ship = '<b>' . "يضيف  <span style =color:green;>SR $add_shipping</span> تحصل <span style =color:green;>الشحن مجانا</span>";
            } else {
                $ship = '<b>' . "أنت مؤهل للشحن المجاني";
            }
        }

        $ship_charge = 0;
        $delivery_charge = 0;

        if (!empty($global[0]->shipping_charge) && !empty($global[0]->min_amount_shipping)) {
            if ($order_price >= $global[0]->min_amount_shipping) {
                $ship_charge = 0;
            } else {
                $ship_charge = $global[0]->shipping_charge;
            }
        }

        if ($request->payment == 2) {
            $delivery_charge = $global[0]->delivery_charge;
        }
        $order_price_subtotal = $order_price;
        $order_price = $order_price + $ship_charge + $delivery_charge;
        Session::forget('coupan_code');

        if ((\App::getLocale() == 'en')) {
            return response()->json(['success' => 'Product Quantity Updated', 'status' => '1', 'order_price' => $order_price, 'ship_charge' => $ship_charge, 'delivery_charge' => $delivery_charge, 'order_price_subtotal' => $order_price_subtotal, 'ship' => $ship, 'producttotalcountvalue' => $producttotalcountvalue]);
        } else {
            return response()->json(['success' => 'تم تحديث كمية المنتج', 'status' => '1', 'order_price' => $order_price, 'ship_charge' => $ship_charge, 'delivery_charge' => $delivery_charge, 'order_price_subtotal' => $order_price_subtotal, 'ship' => $ship, 'producttotalcountvalue' => $producttotalcountvalue]);
        }

    }

    public function categorylist(Request $request, $id)
    {
        $products = DB::table('products')->select('*', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))->where('status', 1)->where('category_id', base64_decode($id))->orderby('id', 'desc')->paginate(52);
        foreach ($products as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');
            $value->category_name = str_replace(' ', '-', $category_name);
            $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);

        }
        $categoryid = base64_decode($id);

        $category = Category::where('id', base64_decode($id))->first();

        if ((\App::getLocale() == 'en')) {
            $cat_name = $category->category_name_en;
        } else {
            $cat_name = $category->category_name_ar;
        }

        $maxprice = 3000;
        $minprice = 0;

        return view('frontend.product-list', compact('products', 'maxprice', 'minprice', 'cat_name', 'categoryid'));
    }

    public function subscribe_email(Request $request)
    {
        $rules = [
            'subscribeemail' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
        ];

        $messages = [
            'subscribeemail.required' => 'Please Enter Email',
            'subscribeemail.regex' => 'Please Enter Valid Email',
        ];

        $v = Validator::make($request->all(), $rules, $messages);
        if ($v->fails()) {
            return redirect()->back()->withInput()->withErrors($v);
        }

        $data = Subscribe_email::where('email', $request->subscribeemail)->first();

        if (empty($data)) {
            $subscribe = new Subscribe_email;
            $subscribe->email = $request->subscribeemail;
            $subscribe->save();

            if ((\App::getLocale() == 'en')) {
                toastr()->success('Email Subscribed Successfully');
            } else {
                toastr()->success('تم الاشتراك بالبريد الإلكتروني بنجاح');
            }

            return redirect(url()->previous());
        }

        if ((\App::getLocale() == 'en')) {
            toastr()->success('Email Already Subscribed');
        } else {
            toastr()->success('البريد الإلكتروني مشترك بالفعل');
        }

        return redirect(url()->previous());

    }

    public function coupancodevalidation(Request $request)
    {

        $coupan = Coupon::where('code', $request->coupan)->first();

        if (empty($coupan)) {
            if ((\App::getLocale() == 'en')) {
                return response()->json(['error' => 'Invalid Coupan Code', 'status' => '2']);
            } else {
                return response()->json(['error' => 'كود الكوبان غير صالح', 'status' => '2']);
            }
        }

        $product = Session::get('product');

        $new_array = array();

        foreach ($product as $key => $value) {
            if (!empty($value)) {
                $new_array[$key] = $value;
            }
        }

        $product_categories = DB::table('products')->select('category_id')->where('status', 1)->whereIn('id', array_keys($new_array))->groupby('category_id')->get();
        $product_subcategories = DB::table('products')->select('sub_category_id')->where('status', 1)->whereIn('id', array_keys($new_array))->groupby('sub_category_id')->get();

        $sub_cat = array();
        foreach ($product_subcategories as $subcategories) {
            $subcate = explode(',', $subcategories->sub_category_id);
            foreach ($subcate as $subcatedata) {
                array_push($sub_cat, $subcatedata);
            }
        }
        $cat = array();
        foreach ($product_categories as $categories) {
            array_push($cat, $categories->category_id);
        }
        $SubCategoryCheck = [];
        $CategoryCheck = [];
        if (!empty($coupan->subcategories)) {
            $allsub = explode(",", $coupan->subcategories);

            $containsSearch = count(array_intersect($sub_cat, $allsub)) >= 1;
            $SubCategoryCheck = array_intersect($sub_cat, $allsub);

            // if ($containsSearch == '') {
            //     if ((\App::getLocale() == 'en')) {
            //         return response()->json(['error' => 'Coupan Code is not valid on this Category', 'status' => '2']);
            //     } else {
            //         return response()->json(['error' => 'كود الكوبان غير صالح في هذه الفئة', 'status' => '2']);
            //     }
            // }

        }

        if (!empty($coupan->categories)) {
            $allcat = explode(",", $coupan->categories);

            $contains_Search_categories = count(array_intersect($cat, $allcat)) >= 1;
            $CategoryCheck = array_intersect($cat, $allcat);
            // if ($contains_Search_categories == '') {
            //     if ((\App::getLocale() == 'en')) {
            //         return response()->json(['error' => 'Coupan Code is not valid on this Category', 'status' => '2']);
            //     } else {
            //         return response()->json(['error' => 'كود الكوبان غير صالح في هذه الفئة', 'status' => '2']);
            //     }
            // }

        }
        if (count($CategoryCheck) == 0 && count($SubCategoryCheck) == 0) {
            if ((\App::getLocale() == 'en')) {
                return response()->json(['error' => 'Coupan Code is not valid on this Category', 'status' => '2']);
            } else {
                return response()->json(['error' => 'كود الكوبان غير صالح في هذه الفئة', 'status' => '2']);
            }
        }

        $products = DB::table('products')->select('*')->where('status', 1)->whereIn('id', array_keys($product))->orderby('id', 'desc')->get();
        $order_price = 0;
        $coupanApplyOrderPrice = 0;
        $productdata = array();
        foreach ($products as $productnew) {

            foreach ($product[$productnew->id] as $key => $colorpro) {
                if ($colorpro['id'] == $productnew->id) {
                    $productdata[$productnew->id][$key]['req_quantity'] = $colorpro['qty'];
                    $productdata[$productnew->id][$key]['color'] = $key;
                    $productdata[$productnew->id][$key]['quantity'] = $productnew->quantity;
                    $productdata[$productnew->id][$key]['id'] = $productnew->id;
                    $productdata[$productnew->id][$key]['name_en'] = $productnew->name_en;
                    $productdata[$productnew->id][$key]['name_ar'] = $productnew->name_ar;
                    $productdata[$productnew->id][$key]['img'] = $productnew->img;
                    $productdata[$productnew->id][$key]['price'] = $productnew->price;
                    $productdata[$productnew->id][$key]['offer_price'] = $productnew->offer_price;
                    $productdata[$productnew->id][$key]['discount_available'] = $productnew->discount_available;
                }

                if (in_array($productnew->category_id, $CategoryCheck) || in_array($productnew->sub_category_id, $SubCategoryCheck)) {

                    $coupanApplyOrderPrice = $coupanApplyOrderPrice + $productdata[$productnew->id][$key]['req_quantity'] * $productdata[$productnew->id][$key]['price'];

                } else {
                    $order_price = $order_price + $productdata[$productnew->id][$key]['req_quantity'] * $productdata[$productnew->id][$key]['price'];
                }

            }
        }
        //  $order_price = $coupanApplyOrderPrice;
        // dd($coupanApplyOrderPrice,$order_price);
        $coupon_uses = DB::table('coupon_history')->where('id', $coupan->id)->where('status', 1)->count();
        $coupan_use_per_customer = DB::table('coupon_history')->where('id', $coupan->id)->where('id', Auth::id())->where('status', 1)->count();

        $currentDate = date('Y-m-d');
        $currentDate = date('Y-m-d', strtotime($currentDate));
        $start_date = $coupan->start_date;
        $start_date = date('Y-m-d', strtotime($start_date));
        $end_date = $coupan->end_date;
        $end_date = date('Y-m-d', strtotime($end_date));

        if (($currentDate >= $start_date) && ($currentDate <= $end_date)) {

            if ($coupon_uses <= $coupan->uses_per_coupon) {
                if ($coupan->uses_per_customer >= $coupan_use_per_customer) {
                    if ($coupanApplyOrderPrice >= $coupan->total_amount) {
                        if ($coupan->type == 1) {
                            $discounted_price = ($coupanApplyOrderPrice / 100) * $coupan->discount;
                            $discount = number_format((float) $discounted_price, 2, '.', '');
                            $discounted_price = $coupanApplyOrderPrice - number_format((float) $discounted_price, 2, '.', '');
                        } else {
                            $discounted_price = $coupan->discount;
                            $discount = number_format((float) $discounted_price, 2, '.', '');
                            $discounted_price = $coupanApplyOrderPrice - number_format((float) $discounted_price, 2, '.', '');
                        }

                        $global = Global_settings::all();
                        $ship_charge = 0;
                        $delivery_charge = 0;
                        $order_price = $order_price + $discounted_price;
                        if (!empty($global[0]->shipping_charge) && !empty($global[0]->min_amount_shipping)) {
                            if ($order_price >= $global[0]->min_amount_shipping) {
                                $ship_charge = 0;
                            } else {
                                $ship_charge = $global[0]->shipping_charge;
                            }
                        }

                        if ($request->payment == 2) {
                            $delivery_charge = $global[0]->delivery_charge;
                        }

                        $discounted_price = $order_price + $ship_charge + $delivery_charge;

//   dd($discount,$ship_charge,$order_price,$coupanApplyOrderPrice,$discounted_price);
                        Session::put('coupan_code', $coupan->id);

                        if ((\App::getLocale() == 'en')) {
                            return response()->json(['success' => 'Coupon Applied Successfully', 'status' => '1', 'ship_charge' => $ship_charge, 'discountprice' => $discounted_price, 'discount' => $discount, 'delivery_charge' => $delivery_charge, 'lang' => 1]);
                        } else {
                            return response()->json(['success' => 'تم تطبيق القسيمة بنجاح', 'status' => '1', 'ship_charge' => $ship_charge, 'discountprice' => $discounted_price, 'discount' => $discount, 'delivery_charge' => $delivery_charge, 'lang' => 2]);
                        }
                    } else {

                        if ((\App::getLocale() == 'en')) {
                            return response()->json(['error' => 'Order Amount Must Be Greater than ' . $coupan->total_amount . ' To Apply This PromoCode', 'status' => '2']);
                        } else {
                            return response()->json(['error' => 'يجب أن يكون مبلغ الطلب أكبر من ' . $coupan->total_amount . ' لتطبيق هذا الرمز الترويجي', 'status' => '2']);
                        }
                    }
                } else {
                    if ((\App::getLocale() == 'en')) {
                        return response()->json(['error' => 'Coupan Usage Maximum Limit Exists', 'status' => '2']);
                    } else {
                        return response()->json(['error' => 'يوجد الحد الأقصى لاستخدام القارورة', 'status' => '2']);
                    }
                }

            } else {
                if ((\App::getLocale() == 'en')) {
                    return response()->json(['error' => 'Coupan Usage Maximum Limit Exists', 'status' => '2']);
                } else {
                    return response()->json(['error' => 'يوجد الحد الأقصى لاستخدام القارورة', 'status' => '2']);
                }
            }

        } else {
            if ((\App::getLocale() == 'en')) {
                return response()->json(['error' => 'Coupan Code Is Expired', 'status' => '2']);
            } else {
                return response()->json(['error' => 'كود الكوبان منتهي الصلاحية', 'status' => '2']);
            }
        }

    }

    public function order_track(Request $request, $id)
    {
        $order_id = base64_decode($id);

        $order = Order::select('*', DB::raw('(select fulladdress from address where address.id=order.address_id)as address'), DB::raw('(select count(id) from order_details where order_id=order.id)as itemcount'))->where('id', $order_id)->first();

        $order_details = Order_details::select('*', DB::raw('(select img from products where products.id=order_details.product_id)as productimage'))->where('order_id', $order_id)->get();

        $order_track_data = Order_track::select('id', 'order_status', DB::raw('(select status_name_en from order_status where order_status.id=order_track.order_status)as order_status_name'))->where('order_id', $order_id)->orderby('id')->get();

        $ordertrack = array();
        foreach ($order_track_data as $track => $values) {
            $ordertrack[$track] = $values->order_status;
            if (!in_array($values->order_status, $ordertrack)) {
                array_push($ordertrack, $ordertrack[$track]);
            }

        }

        $ordertrack = implode(',', $ordertrack);

        return view('frontend.order_traking', compact('order', 'order_details', 'order_track_data', 'ordertrack'));

    }

    public function addtocartdetail(Request $request)
    {
        $id = $request->id;
        $quantity = $request->quantity;
        $color = $request->color;

        $productoptions = $request->productoptions;
        $optionvalues = $request->optionvalues;

        if (!empty($productoptions)) {
            foreach ($productoptions as $key => $options) {
                if (empty($optionvalues[$key])) {
                    if ((\App::getLocale() == 'en')) {
                        return response()->json(['success' => 'Please Select ' . $productoptions[$key], 'status' => '2']);
                    } else {
                        return response()->json(['success' => 'الرجاء التحديد ' . $productoptions[$key], 'status' => '2']);
                    }
                }
            }

            $product_option_ids = array();
            foreach ($productoptions as $optionids) {
                $productdoptionid = DB::table('option')->where('name_en', $optionids)->first();
                array_push($product_option_ids, $productdoptionid->id);
            }

            $options = array_combine($product_option_ids, $optionvalues);

            foreach ($options as $key => $optionvalues) {
                $productdoptionid = DB::table('option_values')->where('option_id', $key)->where('value', $optionvalues)->first();
                $options[$key] = $productdoptionid->id;
            }

            $product_detail_ids = array();

            foreach ($options as $key => $optiondata) {
                $productdatadetail = Product_details::where('product_id', $id)->where('color', $color)->where('option_id', $key)->where('option_value', $optiondata)->first();
                if ($quantity > $productdatadetail->quantity) {
                    if ((\App::getLocale() == 'en')) {
                        return response()->json(['success' => 'Only ' . $productdatadetail->quantity . ' Quantity Left', 'status' => '2']);
                    } else {
                        return response()->json(['success' => 'فقط ' . $productdatadetail->quantity . ' الكمية المتبقية', 'status' => '2']);
                    }
                }
                array_push($product_detail_ids, $productdatadetail->id);

            }

            $color = implode(',', $product_detail_ids);

        } else {
            if (!empty($color)) {
                $productdatadetail = Product_details::where('product_id', $id)->where('color', $color)->first();

                if ($quantity > $productdatadetail->quantity) {
                    if ((\App::getLocale() == 'en')) {
                        return response()->json(['success' => 'Only ' . $productdatadetail->quantity . ' Quantity Left', 'status' => '2']);
                    } else {
                        return response()->json(['success' => 'فقط ' . $productdatadetail->quantity . ' الكمية المتبقية', 'status' => '2']);
                    }

                }

                $color = $productdatadetail->id;
            }

        }

        $productdetail = Product_details::where('product_id', $id)->where('color', $color)->first();
        if (!empty($productdetail)) {
            if ($quantity > $productdetail->quantity) {
                if ((\App::getLocale() == 'en')) {
                    return response()->json(['success' => 'Only ' . $productdetail->quantity . ' Quantity Left Of This Color', 'status' => '2']);
                } else {
                    return response()->json(['success' => 'فقط ' . $productdetail->quantity . ' الكمية المتبقية من هذا اللون', 'status' => '2']);
                }
            }
        } else {
            $productdata = Product::where('id', $id)->first();
            if (!empty($productdata)) {
                if ($quantity > $productdata->quantity) {
                    if ((\App::getLocale() == 'en')) {
                        return response()->json(['success' => 'Only ' . $productdata->quantity . ' Quantity Left', 'status' => '2']);
                    } else {
                        return response()->json(['success' => 'فقط ' . $productdata->quantity . ' الكمية المتبقية', 'status' => '2']);
                    }
                }

            }
        }

        if ($color == '') {
            $color = 'nocolor';
        }
        $product_info = DB::table('products')
            ->select(
                'products.id',
                'products.name_en as product_name',
                'products.price',
                'products.category_id',
                'products.sub_category_id',
                'category.category_name_en',
                'sub_category.sub_category_name_en'
            )
            ->leftJoin('category', 'products.category_id', '=', 'category.id')
            ->leftJoin('sub_category', 'products.sub_category_id', '=', 'sub_category.id')
            ->where('products.id', $id)
            ->first();

        $product = Session::get('product');
        if (!empty($product)) {
            if (array_key_exists($id, $product) && array_key_exists($color, $product)) {
                unset($product[$id][$color]);
                Session::put('product', $product);

                $product[$id][$color] = array(
                    "id" => $id,
                    "qty" => $quantity,
                    "color" => $color,
                );

                Session::put('product', $product);
                $product = Session::get('product');

                if ((\App::getLocale() == 'en')) {
                    return response()->json(['success' => 'Product Added to Cart', 'status' => '1', 'product' => $product_info]);
                } else {
                    return response()->json(['success' => 'تمت إضافة المنتج إلى عربة التسوق', 'status' => '1', 'product' => $product_info]);
                }
            } else {

                $product[$id][$color] = array(
                    "id" => $id,
                    "qty" => $quantity,
                    "color" => $color,
                );

                Session::put('product', $product);

                if ((\App::getLocale() == 'en')) {
                    return response()->json(['success' => 'Product Added to Cart', 'status' => '1', 'product' => $product_info]);
                } else {
                    return response()->json(['success' => 'تمت إضافة المنتج إلى عربة التسوق', 'status' => '1', 'product' => $product_info]);
                }
            }
        }

        if (empty($product)) {
            $product[$id][$color] = array(
                "id" => $id,
                "qty" => 1,
                "color" => $color,
            );

            Session::put('product', $product);
            if ((\App::getLocale() == 'en')) {
                return response()->json(['success' => 'Product Added to Cart', 'status' => '1', 'product' => $product_info]);
            } else {
                return response()->json(['success' => 'تمت إضافة المنتج إلى عربة التسوق', 'status' => '1', 'product' => $product_info]);
            }
        }

    }

    public function order_cancel(Request $request, $id)
    {
        $order_id = base64_decode($id);
        $order = Order::where('id', $order_id)->update(['status' => 6, 'cancellation_reason' => $request->cancel_reason]);
        $order = Order::where('id', $order_id)->first();
        $wallet = Wallet::where('user_id', $order->user_id)->first();
        Wallet::where('user_id', $order->user_id)->update(['amount' => $order->paid_by_wallet + $wallet->amount]);
        $wallethistory = new Wallet_recharge_history;
        $wallethistory->amount = $order->paid_by_wallet;
        $wallethistory->user_id = $order->user_id;
        $wallethistory->type = 1;
        $wallethistory->reason = "Return";
        $wallethistory->reason_ar = "إرجاع";

        $wallethistory->save();

        $products = DB::table('order_details')->select('*')->where('status', 1)->where('order_id', $order_id)->orderby('id', 'desc')->get();

        foreach ($products as $productnew) {
            if ($productnew->color == 'nocolor') {
                $product = DB::table('products')->select('*')->where('status', 1)->where('id', $productnew->product_id)->orderby('id', 'desc')->first();

                $quantity = $productnew->quantity + $product->quantity;
                DB::table('products')->select('*')->where('status', 1)->where('id', $productnew->product_id)->update(['quantity' => $quantity]);
            } else {
                $product = DB::table('product_details')->select('*')->where('status', 1)->where('id', $productnew->color)->first();
                $product1 = DB::table('products')->select('*')->where('status', 1)->where('id', $productnew->product_id)->orderby('id', 'desc')->first();

                $quantity = $productnew->quantity + $product->quantity;
                $quantity1 = $productnew->quantity + $product1->quantity;

                DB::table('product_details')->select('*')->where('status', 1)->where('id', $productnew->color)->update(['quantity' => $quantity]);
                DB::table('products')->select('*')->where('status', 1)->where('id', $productnew->product_id)->update(['quantity' => $quantity1]);

            }

        }

        if ((\App::getLocale() == 'en')) {
            toastr()->success('Order Cancelled Successfully');
        } else {
            toastr()->success('تم إلغاء الطلب بنجاح');
        }
        return redirect('');

    }

    public function brands(Request $request)
    {
        $topbrands = DB::table('brands')->select('*')->where('status', 1)->orderby('id', 'desc')->get();
        return view('frontend.brands-selects', compact('topbrands'));
    }

    public function searchfilter(Request $request)
    {
        if (empty($request->page)) {
            $subcatedata = $request->subcategories[0];
            Session::put('subcatedata', $subcatedata);
        }

        $subcategory = explode(',', Session::get('subcatedata'));

        if (!empty($request->min) && !empty($request->max)) {

            Session::put('min', (int) $request->min);
            Session::put('max', (int) $request->max);

            $min = Session::get('min');
            $max = Session::get('max');
        } else {
            $min = 0;
            $max = 3000;

        }

        $products = Product::select('*', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))->where('price', '>=', $min)->where('price', '<=', $max)->paginate(52);
        foreach ($products as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');
            $value->category_name = str_replace(' ', '-', $category_name);
            $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);

        }

        $subcat = Session::get('subcatedata');

        $sub = Subcategory::where('id', Session::get('subcatedata'))->first();
        $categoryid = '';
        if (!empty($sub)) {
            $categoryid = $sub->category_id;
        }

        if (!empty($subcategory[0])) {
            $products = Product::select('*', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))->where('price', '>=', $min)->where('price', '<=', $max)->whereIn('sub_category_id', $subcategory)->paginate(52);
            foreach ($products as $key => $value) {
                $category_name = Category::where('id', $value->category_id)->value('category_name_en');
                $value->category_name = str_replace(' ', '-', $category_name);
                $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
                $value->sub_category_name = str_replace(' ', '-', $subcategory_name);

            }
        }

        return view('frontend.product-list', compact('products', 'subcat', 'min', 'max', 'categoryid'));

    }

    public function addreview(Request $request)
    {

        $rules = [
            'rating' => 'required',
            'name' => 'required|max:255',
            'review' => 'required',
        ];

        $messages = [
            'rating.required' => 'Please Enter Full Address',

            'name.required' => 'Please Enter Name',
            'name.max' => 'Name Must be less than 255 characters',

            'review.required' => 'Please Enter Review',
        ];

        $v = Validator::make($request->all(), $rules, $messages);
        if ($v->fails()) {
            return redirect()->back()->withInput()->withErrors($v);
        }

        $user_id = Auth::id();
        $rating = $request->rating;
        $name = $request->name;
        $review = $request->review;
        $product_id = $request->product_id;

        $review_images = "";

        $t_image_name = array();
        if (!empty($request['upload_imgs'])) {
            foreach ($request['upload_imgs'] as $t_key => $t_value) {
                $t_file_name = $t_value->getClientOriginalName();
                $t_value->move(public_path() . '/reviewcomment', $t_file_name);
                array_push($t_image_name, $t_file_name);
            }

        }

        if (!empty($t_image_name)) {
            $review_images = implode(',', $t_image_name);
        }

        $check = Product_reviews::where('product_id', $product_id)->where('user_id', $user_id)->first();

        if (!empty($check)) {
            Product_reviews::where('product_id', $product_id)->update(['user_id' => $user_id, 'product_id' => $product_id, 'rating' => $rating, 'name' => $name, 'review' => $review, 'images' => $review_images]);

            if ((\App::getLocale() == 'en')) {
                toastr()->success('Review Updated Successfully');
            } else {
                toastr()->success('مراجعة تم التحديث بنجاح');
            }
            return redirect(url()->previous());

        } else {

            $productreivew = new Product_reviews;
            $productreivew->user_id = $user_id;
            $productreivew->rating = $rating;
            $productreivew->name = $name;
            $productreivew->review = $review;
            $productreivew->images = $review_images;
            $productreivew->product_id = $product_id;

            $productreivew->save();

            if ((\App::getLocale() == 'en')) {
                toastr()->success('Review Added Successfully');
            } else {
                toastr()->success('تمت إضافة المراجعة بنجاح');
            }
            return redirect(url()->previous());
        }
    }

    public function allproducts(Request $request)
    {

        $products = DB::table('most_product_viewed')
            ->select('most_product_viewed.product_id', 'products.id', 'products.name_en', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'products.stock_availabity', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))
            ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
            ->orderby('most_product_viewed.id', 'desc')
            ->where('user_id', Auth::id())
            ->groupby('most_product_viewed.product_id')
            ->paginate(52);
        foreach ($products as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');
            $value->category_name = str_replace(' ', '-', $category_name);
            $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);
        }
        $maxprice = 3000;
        $minprice = 0;

        return view('frontend.product-list', compact('products', 'maxprice', 'minprice'));
    }

    public function newarrivalproducts(Request $request)
    {
        $products = DB::table('products')->select('*', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))->where('status', 1)->orderby('id', 'desc')->paginate(52);

        foreach ($products as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');
            $value->category_name = str_replace(' ', '-', $category_name);
            $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);
        }
        $maxprice = 3000;
        $minprice = 0;

        return view('frontend.product-list', compact('products', 'maxprice', 'minprice'));
    }

    public function smiliar_products(Request $request, $id)
    {
        $id = base64_decode($id);
        $product = DB::table('products')->select('*', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))->where('status', 1)->where('id', $id)->orderby('id', 'desc')->first();

        $products = DB::table('products')->select('*', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))->where('status', 1)->whereIN('id', explode(',', $product->relatedproducts))->orderby('id', 'desc')->paginate(52);
        foreach ($products as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');
            $value->category_name = str_replace(' ', '-', $category_name);
            $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);
        }
        $maxprice = 3000;
        $minprice = 0;

        return view('frontend.product-list', compact('products', 'maxprice', 'minprice'));
    }

    public function hottodayproducts(Request $request)
    {
        $products = DB::table('products')->select('*', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))->where('status', 1)->where('discount_available', '!=', 0)->orderByRaw('RAND()')->paginate(52);
        foreach ($products as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');
            $value->category_name = str_replace(' ', '-', $category_name);
            $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);
        }
        $maxprice = 3000;
        $minprice = 0;

        return view('frontend.product-list', compact('products', 'maxprice', 'minprice'));

    }

    public function tendingproducts(Request $request)
    {
        $products = DB::table('most_product_viewed')
            ->select('most_product_viewed.product_id', 'products.id', 'products.status', 'products.name_en', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'products.stock_availabity', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))
            ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
            ->orderby('most_product_viewed.count', 'desc')
            ->whereNull('user_id')
            ->where('products.status', 1)
            ->groupby('most_product_viewed.product_id')
            ->paginate(52);
        foreach ($products as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');
            $value->category_name = str_replace(' ', '-', $category_name);
            $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);
        }
        $maxprice = 3000;
        $minprice = 0;

        return view('frontend.product-list', compact('products', 'maxprice', 'minprice'));

    }

    public function hotdealproduct(Request $request)
    {
        $products = DB::table('products')->select('*', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))->where('status', 1)->where('discount_available', '!=', 0)->orderBy('discount_available', 'desc')->paginate(52);
        foreach ($products as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');
            $value->category_name = str_replace(' ', '-', $category_name);
            $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);
        }
        $maxprice = 3000;
        $minprice = 0;

        return view('frontend.product-list', compact('products', 'maxprice', 'minprice'));

    }

    public function bestsellingproducts(Request $request)
    {
        $products = DB::table('order_details')
            ->select('order_details.product_id', 'products.id', 'products.status', 'products.name_en', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'products.stock_availabity', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as sum'), DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))
            ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
            ->orderby('sum', 'desc')
            ->where('products.status', 1)
            ->groupby('order_details.product_id')
            ->paginate(52);

        foreach ($products as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');
            $value->category_name = str_replace(' ', '-', $category_name);
            $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);
        }
        $maxprice = 3000;
        $minprice = 0;

        return view('frontend.product-list', compact('products', 'maxprice', 'minprice'));

    }

    public function coloroptions(Request $request)
    {
        $color = $request->id1;
        $product_id = $request->id2;

        $productdata = Product::where('id', $product_id)->first();
        $productdataoption = DB::table('product_details')->where('product_id', $product_id)->where('color', $color)->first();

        $options = Option::with(["productoptions" => function ($q) use ($color, $product_id) {
            $q->where('product_details.product_id', '=', $product_id)->where('product_details.color', '=', $color);
        }])->get();

        $html = '';

        foreach ($options as $key => $option) {
            if (count($option->productoptions) > 0) {
                $html .= '<div class="custom-radio-mul-sec" style="font-size: 20px;">';
                if ((\App::getLocale() == 'en')) {
                    $html .= '<span>' . $option->name_en . '</span>';
                } else {
                    $html .= '<span>' . $option->name_ar . '</span>';
                }
                $html .= '<input type ="hidden" name="product_options[]" value="' . $option->name_en . '">';
                $html .= '<div class="d-flex">';
                foreach ($option->productoptions as $secondkey => $prooptions) {
                    $html .= '<div class="custom-control custom-radio">';
                    $value = DB::table('option_values')->select('value', 'option_value_name_ar')->where('id', $prooptions->option_value)->first();
                    $price = DB::table('product_details')->select('*')->where('product_id', $product_id)->where('color', $color)->where('option_value', $prooptions->option_value)->first();
                    $productprice = DB::table('products')->select('price')->where('id', $product_id)->first();

                    $totalproductprice = $price->price + $productprice->price;
                    if ($price->quantity > 0) {
                        $html .= '<input type="radio" id="' . $prooptions->id . '" onchange ="changeproductprice(' . $totalproductprice . ')" name ="' . $option->name_en . '" value="' . $value->value . '" class="custom-control-input"' . ($option->productoptions[0]->id == $prooptions->id ? ' checked' : '') . '>';
                    } else {
                        $html .= '<input type="radio" disabled id="' . $prooptions->id . '" onchange ="changeproductprice(' . $totalproductprice . ')" name ="' . $option->name_en . '" value="' . $value->value . '" class="custom-control-input"' . '>';
                    }

                    $html .= '<label class="custom-control-label" for="' . $prooptions->id . '">';
                    $html .= '<div class="input-space-tx">';
                    $html .= ' <p class="option-size-name">';
                    if ((\App::getLocale() == 'en')) {
                        $html .= $value->value;
                    } else {
                        $html .= $value->option_value_name_ar;
                    }
                    $html .= '</p>';
                    $html .= '<p class="option-size-price">';
                    $html .= 'SAR ' . $totalproductprice;
                    $html .= '</p>';
                    if ($price->quantity <= 0) {
                        $html .= '<p class="option-size-price" style="color:red;">';
                        if ((\App::getLocale() == 'en')) {
                            $html .= 'Out Of Stock';
                        } else {
                            $html .= ' غير متوفر';
                        }
                        $html .= '</p>';
                    }
                    $html .= '</div>';
                    $html .= '</label>';
                    $html .= '</div>';

                }
                $html .= '</div>';
                $html .= '</div>';
            }
        }

        if (!empty($productdata) && !empty($productdataoption)) {
            $totalprice = $productdata->price + $productdataoption->price;
            $res['price'] = $totalprice;
        }

        $res['message'] = $html;
        return response($res);

    }

    public function autocompleteajax(Request $request)
    {
        $search = $request->term;

        if ((\App::getLocale() == 'en')) {
            $posts = Product::where(function ($query) use ($search) {
                $query->where('name_en', 'LIKE', "%{$search}%")->orWhere('sku_en', 'LIKE', "%{$search}%");
            })
                ->where('status', '1')->limit(5)->get();

        } else {
            $posts = Product::where(function ($query) use ($search) {
                $query->where('name_ar', 'LIKE', "%{$search}%")->orWhere('sku_ar', 'LIKE', "%{$search}%");
            })
                ->where('status', '1')->limit(5)->get();

        }

        foreach ($posts as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');
            $value->category_name = str_replace(' ', '-', $category_name);
            $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);
        }

        if (!$posts->isEmpty()) {
            foreach ($posts as $post) {
                if ((\App::getLocale() == 'en')) {
                    $new_row['title'] = $post->name_en;
                } else {
                    $new_row['title'] = $post->name_ar;
                }
                $new_row['image'] = $post->img;
                $new_row['price'] = $post->price;
                $new_row['url'] = url($post->category_name . '/' . $post->sub_category_name . '/' . $post->seo_url);

                $row_set[] = $new_row; //build an array
            }
        }

        echo json_encode($row_set);
    }

    public function brandproducts(Request $request, $id)
    {
        if ((\App::getLocale() == 'en')) {
            $maxprice = 3000;
            $minprice = 0;
            $brand = DB::table('brands')->select('*')->where('id', base64_decode($id))->first();
            $brand_name = $brand->name_en;

            $products = Product::select('*', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))->where('brand_id', base64_decode($id))->paginate(52);
            foreach ($products as $key => $value) {
                $category_name = Category::where('id', $value->category_id)->value('category_name_en');
                $value->category_name = str_replace(' ', '-', $category_name);
                $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
                $value->sub_category_name = str_replace(' ', '-', $subcategory_name);

            }
            return view('frontend.product-list', compact('products', 'maxprice', 'minprice', 'brand_name'));
        } else {
            $maxprice = 3000;
            $minprice = 0;
            $brand = DB::table('brands')->select('*')->where('id', base64_decode($id))->first();
            $brand_name = $brand->name_ar;
            $products = Product::select('*', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))->where('brand_id', base64_decode($id))->paginate(52);
            foreach ($products as $key => $value) {
                $category_name = Category::where('id', $value->category_id)->value('category_name_en');
                $value->category_name = str_replace(' ', '-', $category_name);
                $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
                $value->sub_category_name = str_replace(' ', '-', $subcategory_name);

            }

            return view('frontend.product-list', compact('products', 'maxprice', 'minprice', 'brand_name'));
        }
    }

    public function recharge(Request $request)
    {
        $rules = [
            'amount' => 'required',
        ];

        $messages = [
            'amount.required' => 'Please Enter Amount',
        ];

        $v = Validator::make($request->all(), $rules, $messages);
        if ($v->fails()) {
            return redirect()->back()->withInput()->withErrors($v);
        }

        $user_data = User::where('id', Auth::id())->first();
        $amount = $request->amount;
        return view('frontend.rechargepayment', compact('amount', 'user_data'));

    }

    public function rechargepayment(Request $request)
    {
        if (empty($request->tap_id)) {
            return redirect('/');
        }

        $charge_id = $request->tap_id;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.tap.company/v2/charges/" . $charge_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "{}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer sk_live_tA8OicMD2sbKCkqxXlmp4TBU",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $result = json_decode($response, true);
            if ($result['status'] == 'CAPTURED') {

                $userdata = User::where('id', Auth::id())->first();
                $name = $userdata->name;

                $EmailTemplates = Emailtemplate::where('slug', 'recharge_completed')->first();
                $message = str_replace(array('{name}'), array($name), $EmailTemplates->description_en);
                $subject = $EmailTemplates->subject_en;
                $to_email = $userdata->email;
                $data = array();
                $data['msg'] = $message;
                Mail::send('emails.emailtemplate', $data, function ($message) use ($to_email, $subject) {
                    $message->to($to_email)
                        ->subject($subject);
                    $message->from(env('MAIL_USERNAME', 'letsbuysa1@gmail.com'));
                });

                $user_mobile = User::select('mobile')->where('id', Auth::id())->first();

                $mobile = $user_mobile->mobile;

                $user = "letsbuy";
                $password = "Nn0450292**";
                $mobilenumbers = $mobile;
                if ((\App::getLocale() == 'en')) {
                    $message = 'Your Wallet Recharege is Successfully Completed Amount :' . $result['amount'];
                } else {
                    $message = ' إعادة مشاركة المحفظة الخاصة بك قد اكتملت بنجاح ' . $result['amount'];
                }
                $senderid = "LetsBuy"; //Your senderid
                $message = urlencode($message);
                $url = "https://www.enjazsms.com/api/sendsms.php?username=" . $user . "&password=" . $password . "&message=" . $message . "&numbers=" . $mobilenumbers . "&sender=LetsBuy&unicode=E&return=null&port=1";
                // create a new cURL resource
                $ch = curl_init();
                // set URL and other appropriate options
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                // grab URL and pass it to the browser
                // close cURL resource, and free up system resources
                $curlresponse = curl_exec($ch);
                curl_close($ch);

                $walletrecharge = new Wallet_recharge_history;
                $walletrecharge->amount = $result['amount'];
                $walletrecharge->user_id = Auth::id();
                $walletrecharge->type = 1;
                $walletrecharge->reason = "Recharge";
                $walletrecharge->reason_ar = "تعبئة رصيد";

                $walletrecharge->save();

                $walletamount = Wallet::where('user_id', Auth::id())->first();
                if (!empty($walletamount)) {
                    Wallet::where('user_id', Auth::id())->update(['amount' => $result['amount'] + $walletamount->amount]);
                } else {
                    $walletupdate = new Wallet;
                    $walletupdate->amount = $result['amount'];
                    $walletupdate->user_id = Auth::id();
                    $walletupdate->save();
                }

                if (!empty($result)) {
                    $transaction = new Transaction_details;
                    $transaction->charge_id = $result['id'];
                    $transaction->payment_status = $result['status'];
                    $transaction->user_id = Auth::id();
                    $transaction->order_id = $walletrecharge->id;
                    $transaction->amount = $result['amount'];
                    $transaction->currency = $result['currency'];
                    $transaction->track_id = $result['reference']['track'];
                    $transaction->payment_id = $result['reference']['payment'];
                    $transaction->transaction_generate_id = $result['reference']['transaction'];
                    $transaction->order_generate_id = $result['reference']['order'];
                    $transaction->receipt_id = $result['receipt']['id'];
                    $transaction->payment_method = $result['source']['payment_method'];
                    $transaction->payment_type = $result['source']['payment_type'];
                    $transaction->token_id = $result['source']['id'];

                    $transaction->save();
                }

                if ((\App::getLocale() == 'en')) {
                    toastr()->success('Recharge Completed Successfully');
                } else {
                    toastr()->success('اكتملت عملية الشحن بنجاح');
                }
                return redirect('/mywallet');
            } else {
                if ((\App::getLocale() == 'en')) {
                    toastr()->success('Your Payment Is Declined By Bank');
                } else {
                    toastr()->success('تم رفض مدفوعاتك من قبل البنك');
                }
                return redirect(URL::to('/mywallet'));
            }
        }

    }

    public function rewardredeem(Request $request)
    {

        $rules = [
            'rewards' => 'required|numeric',
        ];

        $messages = [
            'rewards.required' => 'Please Enter Voucher',
        ];
        $v = Validator::make($request->all(), $rules, $messages);
        if ($v->fails()) {
            return redirect()->back()->withInput()->withErrors($v);
        }

        $rewards = Reward::where('user_id', Auth::id())->first();
        $global = Global_settings::all();

        if (empty($rewards)) {
            if ((\App::getLocale() == 'en')) {
                toastr()->error('You Do Not Have Sufficient Rewards');
            } else {
                toastr()->error('ليس لديك مكافآت كافية');
            }
            return redirect(url()->previous());
        } else {
            if ($request->rewards >= $rewards->reward) {
                if ((\App::getLocale() == 'en')) {
                    toastr()->error('You Do Not Have Sufficient Rewards');
                } else {
                    toastr()->error('ليس لديك مكافآت كافية');
                }
                return redirect(url()->previous());
            } else {
                if ($request->rewards >= $global[0]->minimum_reward_point) {

                    $redeempoints = $request->rewards;

                    $walletamount = Wallet::where('user_id', Auth::id())->first();
                    if (!empty($walletamount)) {
                        Wallet::where('user_id', Auth::id())->update(['amount' => $redeempoints + $walletamount->amount]);
                    } else {
                        $walletupdate = new Wallet;
                        $walletupdate->amount = $redeempoints;
                        $walletupdate->user_id = Auth::id();
                        $walletupdate->save();

                    }

                    $history = new Wallet_recharge_history;
                    $history->amount = $redeempoints;
                    $history->user_id = Auth::id();
                    $history->type = 1;
                    $history->reason = "Reward Redeem";
                    $history->reason_ar = "استرداد المكافأة";

                    $history->save();

                    $updated_reward = $rewards->reward - $redeempoints;
                    Reward::where('user_id', Auth::id())->update(['reward' => $updated_reward]);

                    $history = new Redeem_rewards;
                    $history->reward = $redeempoints;
                    $history->user_id = Auth::id();
                    $history->type = 2;

                    $history->save();

                    if ((\App::getLocale() == 'en')) {
                        toastr()->success('Reward Redeem Successfully');
                    } else {
                        toastr()->success('استرداد المكافأة بنجاح');
                    }
                    return redirect(url()->previous());

                } else {
                    if ((\App::getLocale() == 'en')) {
                        toastr()->error('To Redeem Rewards You Must Enter ' . $global[0]->minimum_reward_point . ' Rewards');
                    } else {
                        toastr()->error('لاسترداد المكافآت ، يجب عليك إدخالها ' . $global[0]->min_reward_point . ' المكافآت');
                    }
                    return redirect(url()->previous());
                }

            }

        }

    }

    public function voucher(Request $request)
    {

        $rules = [
            'voucher' => 'required',
        ];

        $messages = [
            'voucher.required' => 'Please Enter Voucher',
        ];

        $v = Validator::make($request->all(), $rules, $messages);
        if ($v->fails()) {
            return redirect()->back()->withInput()->withErrors($v);
        }

        $voucher = Gift_voucher::where('code', $request->voucher)->where('status', 1)->first();
        $gift_voucher = Gift_voucher_user::where('gift_code', $request->voucher)->where('is_used', 0)->first();

        if (!empty($voucher)) {

            $currentDate = date('Y-m-d');
            $currentDate = date('Y-m-d', strtotime($currentDate));
            $start_date = $voucher->start_date;
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = $voucher->end_date;
            $end_date = date('Y-m-d', strtotime($end_date));

            if (($currentDate >= $start_date) && ($currentDate <= $end_date)) {
                if ($voucher->use_count >= 1) {

                    $walletamount = Wallet::where('user_id', Auth::id())->first();
                    if (!empty($walletamount)) {
                        Wallet::where('user_id', Auth::id())->update(['amount' => $voucher->amount + $walletamount->amount]);
                    } else {
                        $walletupdate = new Wallet;
                        $walletupdate->amount = $voucher->amount;
                        $walletupdate->user_id = Auth::id();
                        $walletupdate->save();

                    }

                    $history = new Wallet_recharge_history;
                    $history->amount = $voucher->amount;
                    $history->user_id = Auth::id();
                    $history->type = 1;
                    $history->reason = "Voucher";
                    $history->reason_ar = "فاتورة";

                    $history->save();

                    Gift_voucher::where('id', $voucher->id)->update(['use_count' => $voucher->use_count - 1, 'is_used' => 1]);

                    if ((\App::getLocale() == 'en')) {
                        toastr()->success('Voucher Applied Successfully');
                    } else {
                        toastr()->success('تم تطبيق القسيمة بنجاح');
                    }
                    return redirect(url()->previous());

                } else {
                    if ((\App::getLocale() == 'en')) {
                        toastr()->error('Voucher Usage Maximum Limit Exists');
                    } else {
                        toastr()->error('يوجد الحد الأقصى لاستخدام القسيمة');
                    }
                    return redirect(url()->previous());

                }
            } else {
                if ((\App::getLocale() == 'en')) {
                    toastr()->error('Voucher Code Is Expired');
                } else {
                    toastr()->error('انتهت صلاحية رمز القسيمة');
                }
                return redirect(url()->previous());

            }

        } elseif (!empty($gift_voucher)) {
            $walletamount = Wallet::where('user_id', Auth::id())->first();
            if (!empty($walletamount)) {
                Wallet::where('user_id', Auth::id())->update(['amount' => $gift_voucher->amount + $walletamount->amount]);
            } else {
                $walletupdate = new Wallet;
                $walletupdate->amount = $gift_voucher->amount;
                $walletupdate->user_id = Auth::id();
                $walletupdate->save();

            }

            $history = new Wallet_recharge_history;
            $history->amount = $gift_voucher->amount;
            $history->user_id = Auth::id();
            $history->type = 1;
            $history->reason = "Voucher";
            $history->reason_ar = "فاتورة";

            $history->save();

            Gift_voucher_user::where('id', $gift_voucher->id)->update(['is_used' => 1]);

            if ((\App::getLocale() == 'en')) {
                toastr()->success('Voucher Applied Successfully');
            } else {
                toastr()->success('تم تطبيق القسيمة بنجاح');
            }
            return redirect(url()->previous());

        } else {

            if ((\App::getLocale() == 'en')) {
                toastr()->error('Please Enter Valid Voucher');
            } else {
                toastr()->error('الرجاء إدخال قسيمة صالحة');
            }
            return redirect(url()->previous());

        }

    }

    public function mostviewedelete(Request $request)
    {
        $data = Mostviewedproduct::
            select('id')
            ->whereDate('created_at', '<', Carbon::now()->subDays(1))
            ->whereNotNull('user_id')
            ->delete();
        return response($data);
    }

    public function gift_send(Request $request)
    {
        $rules = [
            'amount' => 'required|max:10',
            'recipient_email' => 'required|max:50',
            'recipient_phone' => 'required|max:20',
            'recipient_name' => 'required|max:50',
            'sender_name' => 'required|max:50',
            'message' => 'required|max:1000',
        ];

        $messages = [
            'amount.required' => 'Please Enter Amount',
            'recipient_email.required' => 'Please Enter Recipient Email',
            'recipient_phone.required' => 'Please Enter Recipient Phone',
            'recipient_name.required' => 'Please Enter Recipient Name',
            'sender_name.required' => 'Please Enter Sender Name',
            'message.required' => 'Please Enter Message',
        ];

        $v = Validator::make($request->all(), $rules, $messages);
        if ($v->fails()) {
            return redirect()->back()->withInput()->withErrors($v);
        }
        $code = $this->generateRandomString();

        $giftvoucher = new Gift_voucher_user;
        $giftvoucher->amount = $request->amount;
        $giftvoucher->sender_id = Auth::id();
        $giftvoucher->recipient_email = $request->recipient_email;
        $giftvoucher->recipient_phone = $request->recipient_phone;
        $giftvoucher->recipient_name = $request->recipient_name;
        $giftvoucher->sender_name = $request->sender_name;
        $giftvoucher->message = $request->message;
        $giftvoucher->gift_code = $code;
        $giftvoucher->save();

        Session::put('giftid', $giftvoucher->id);

        $user_data = User::where('id', Auth::id())->first();
        $amount = $request->amount;
        return view('frontend.giftvoucherpayment', compact('amount', 'user_data'));
    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function giftvoucherpayment(Request $request)
    {
        if (empty($request->tap_id)) {
            return redirect('/');
        }

        $charge_id = $request->tap_id;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.tap.company/v2/charges/" . $charge_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "{}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer sk_live_tA8OicMD2sbKCkqxXlmp4TBU",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $result = json_decode($response, true);
            if ($result['status'] == 'CAPTURED') {

                $userdata = User::where('id', Auth::id())->first();
                $name = $userdata->name;

                $giftdetails = Gift_voucher_user::where('id', Session::get('giftid'))->first();
                $name = $giftdetails->recipient_name;
                $sender_name = $giftdetails->sender_name;
                $code = $giftdetails->gift_code;
                $amount = $giftdetails->amount;
                $message = $giftdetails->message;

                $EmailTemplates = Emailtemplate::where('slug', 'gift_voucher')->first();
                $message = str_replace(array('{name}', '{sender_name}', '{code}', '{amount}', '{message}'), array($name, $code, $sender_name, $amount, $message), $EmailTemplates->description_en);
                $subject = $EmailTemplates->subject_en;
                $to_email = $giftdetails->recipient_email;
                $data = array();
                $data['msg'] = $message;
                Mail::send('emails.emailtemplate', $data, function ($message) use ($to_email, $subject) {
                    $message->to($to_email)
                        ->subject($subject);
                    $message->from(env('MAIL_USERNAME', 'letsbuysa1@gmail.com'));
                });

                $user_mobile = User::select('mobile')->where('id', Auth::id())->first();

                $mobile = $user_mobile->mobile;

                $user = "letsbuy";
                $password = "Nn0450292**";
                $mobilenumbers = $mobile;
                if ((\App::getLocale() == 'en')) {
                    $message = 'Your Gift is Successfully Sent To ' . $giftdetails->recipient_name;
                } else {
                    $message = 'تم إرسال هديتك بنجاح إلى ' . $giftdetails->recipient_name;
                }
                $senderid = "LetsBuy"; //Your senderid
                $message = urlencode($message);
                $url = "https://www.enjazsms.com/api/sendsms.php?username=" . $user . "&password=" . $password . "&message=" . $message . "&numbers=" . $mobilenumbers . "&sender=LetsBuy&unicode=E&return=null&port=1";
                // create a new cURL resource
                $ch = curl_init();
                // set URL and other appropriate options
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                // grab URL and pass it to the browser
                // close cURL resource, and free up system resources
                $curlresponse = curl_exec($ch);
                curl_close($ch);

                Gift_voucher_user::where('id', Session::get('giftid'))->update(['is_paid' => 1]);

                if (!empty($result)) {
                    $transaction = new Transaction_details;
                    $transaction->charge_id = $result['id'];
                    $transaction->payment_status = $result['status'];
                    $transaction->user_id = Auth::id();
                    $transaction->order_id = Session::get('giftid');
                    $transaction->amount = $result['amount'];
                    $transaction->currency = $result['currency'];
                    $transaction->track_id = $result['reference']['track'];
                    $transaction->payment_id = $result['reference']['payment'];
                    $transaction->transaction_generate_id = $result['reference']['transaction'];
                    $transaction->order_generate_id = $result['reference']['order'];
                    $transaction->receipt_id = $result['receipt']['id'];
                    $transaction->payment_method = $result['source']['payment_method'];
                    $transaction->payment_type = $result['source']['payment_type'];
                    $transaction->token_id = $result['source']['id'];

                    $transaction->save();
                }

                if ((\App::getLocale() == 'en')) {
                    toastr()->success('Voucher Sent Successfully');
                } else {
                    toastr()->success('اكتملت عملية الشحن بنجاح');
                }
                return redirect('/mygifts');
            } else {
                if ((\App::getLocale() == 'en')) {
                    toastr()->success('Your Payment Is Declined By Bank');
                } else {
                    toastr()->success('تم رفض مدفوعاتك من قبل البنك');
                }
                return redirect(URL::to('/mywallet'));
            }
        }

    }

    public function productCategorySet($id1, $id2)
    {

        $id1 = Category::where('category_name_en', str_replace('-', ' ', $id1))->value('id');

        $id2 = Subcategory::where('sub_category_name_en', str_replace('-', ' ', $id2))->value('id');

        // $id2 =base64_decode($id2);
        $products = DB::table('products')->select('*', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))->where('status', 1)->where('category_id', $id1)->whereRaw('FIND_IN_SET(' . $id2 . ',sub_category_id)')->orderby('id', 'desc')->paginate(52);
        // dd($id1,$id2,$products);
        foreach ($products as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');
            $value->category_name = str_replace(' ', '-', $category_name);
            $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);

        }

        $categoryid = $id1;
        $subcategoryid = $id2;

        $subcategory = Subcategory::where('id', $id2)->first();

        if ((\App::getLocale() == 'en')) {
            $subcat_name = $subcategory->sub_category_name_en;
        } else {
            $subcat_name = $subcategory->sub_category_name_ar;
        }

        $maxprice = 3000;
        $minprice = 0;

        return view('frontend.product-list', compact('products', 'maxprice', 'minprice', 'subcat_name', 'categoryid', 'subcategoryid'));

    }
    public function CategorySet($id)
    {
        $id = Category::where('category_name_en', str_replace('-', ' ', $id))->value('id');

        $products = DB::table('products')->select('*', DB::raw('(select name_en from brands where brands.id=products.brand_id) as brandname_en'), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname_ar'))->where('status', 1)->where('category_id', $id)->orderby('id', 'desc')->paginate(52);
        foreach ($products as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');
            $value->category_name = str_replace(' ', '-', $category_name);
            $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);

        }
        $categoryid = $id;

        $category = Category::where('id', $id)->first();

        if ((\App::getLocale() == 'en')) {
            $cat_name = $category->category_name_en;
        } else {
            $cat_name = $category->category_name_ar;
        }

        $maxprice = 3000;
        $minprice = 0;

        return view('frontend.product-list', compact('products', 'maxprice', 'minprice', 'cat_name', 'categoryid'));
    }
    public function checkoutpaymentByHyperPay($address_id, $types)
    {
        $coupan_code = Session::get('coupan_code');

        $coupan = Coupon::where('id', $coupan_code)->first();
        $CategoryCheck = [];
        $SubCategoryCheck = [];

        if ($coupan != null) {
            if ($coupan->subcategories != null) {

                $SubCategoryCheck = explode(',', $coupan->subcategories);
            }
            if ($coupan->categories != null) {
                $CategoryCheck = explode(',', $coupan->categories);
            }
        }

        if (!is_numeric(base64_decode($address_id))) {
            return redirect(url()->previous());
        }
        if ($types != 1 && $types != 3) {
            return redirect()->route('checkoutpayment');
        }

        if (!empty($address_id)) {
            Session::put('address_id', base64_decode($address_id));
        }

        $address = Address::where('id', Session::get('address_id'))->first();

        $product = Session::get('product');
        if ($product == null) {
            return redirect('/');
        }
        $products = DB::table('products')->select('*')->where('status', 1)->whereIn('id', array_keys($product))->orderby('id', 'desc')->get();
        $coupanApplyOrderPrice = 0;
        $order_price = 0;
        $productdata = array();
        foreach ($products as $productnew) {
            foreach ($product[$productnew->id] as $key => $colorpro) {
                if ($colorpro['id'] == $productnew->id) {
                    $productdata[$productnew->id][$key]['req_quantity'] = $colorpro['qty'];
                    $productdata[$productnew->id][$key]['quantity'] = $productnew->quantity;
                    $productdata[$productnew->id][$key]['id'] = $productnew->id;
                    $productdata[$productnew->id][$key]['name_en'] = $productnew->name_en;
                    $productdata[$productnew->id][$key]['name_ar'] = $productnew->name_ar;
                    $productdata[$productnew->id][$key]['img'] = $productnew->img;
                    if ($key != 'nocolor') {
                        $product_detail_option = explode(',', $key);
                        $products1 = DB::table('product_details')->select('*')->where('status', 1)->where('id', $product_detail_option[0])->first();
                        $productdata[$productnew->id][$key]['price'] = $productnew->price + $products1->price;
                    } else {
                        $productdata[$productnew->id][$key]['price'] = $productnew->price;
                    }
                    $productdata[$productnew->id][$key]['offer_price'] = $productnew->offer_price;
                    $productdata[$productnew->id][$key]['discount_available'] = $productnew->discount_available;

                }
                if (in_array($productnew->category_id, $CategoryCheck) || in_array($productnew->sub_category_id, $SubCategoryCheck)) {

                    $coupanApplyOrderPrice = $coupanApplyOrderPrice + $productdata[$productnew->id][$key]['req_quantity'] * $productdata[$productnew->id][$key]['price'];

                } else {
                    $order_price = $order_price + $productdata[$productnew->id][$key]['req_quantity'] * $productdata[$productnew->id][$key]['price'];
                }

            }
        }

        $global = Global_settings::all();
        $ship_charge = 0;
        $discounted_price = 0;
        if ($coupan != null) {
            $discounted_price = number_format((float) ($coupanApplyOrderPrice / 100) * $coupan->discount, 2, '.', '');
        }
        if (!empty($global[0]->shipping_charge) && !empty($global[0]->min_amount_shipping)) {
            if ($order_price >= $global[0]->min_amount_shipping) {
                $ship_charge = 0;
            } else {
                $ship_charge = $global[0]->shipping_charge;
            }
        }

        $order_price = $order_price + $coupanApplyOrderPrice - (float) $discounted_price;

        $order_price_subtotal = $order_price;
        $order_price = $order_price + $ship_charge;

        $delivery_charge = $global[0]->delivery_charge;

        $walletLessAmount = 0;

        if (Session::get('wallet') == '1') {
            $wallet = Wallet::where('user_id', Auth::id())->first();
            $walletamount = $wallet->amount;

            if ($walletamount >= $order_price) {
                if ((\App::getLocale() == 'en')) {
                    toastr()->success('Your Payment Price Is Zero');
                } else {
                    toastr()->success('سعر الدفع الخاص بك هو صفر');
                }
                return redirect()->back();
            } else {
                $walletLessAmount = $order_price;
                $order_price = $order_price > $walletamount ? $order_price - $walletamount : $walletamount - $order_price;

            }

        }

        $wallet = Wallet::where('user_id', Auth::id())->first();

        if ($types == 1) {
            $entityId = "8ac9a4c97dbd4ae3017dc315fc7562da"; //live
            $method = "VISA MASTER";
        }
        if ($types == 3) {
            $entityId = "8ac9a4c97dbd4ae3017dc31689fc62e3"; //live
            $method = "MADA";
        }
        // dd(base64_decode($address_id));
        $getaddress = Address::leftjoin('country', 'country.country_id', '=', 'address.country')
            ->where('id', base64_decode($address_id))
            ->select('address.*', 'country.iso_code_2')
            ->first();
        $getUser = User::where('id', Auth::id())->first();
        $milliseconds = date_create()->format('Uv');

        // -------deepak work start-------------//
        $url = "https://eu-prod.oppwa.com/v1/checkouts";
        $data = "entityId=" . $entityId .
        "&amount=" . number_format((float) $order_price, 2, '.', '') .
        "&currency=SAR" .
        "&paymentType=DB" .
        "&customer.email=" . $getUser->email ?? 'test@getnada.com';
        $data .= "&merchantTransactionId=" . Auth::id() . $milliseconds;
        $data .= "&billing.street1=" . $getaddress->fulladdress ?? 'Jaipur';
        $data .= "&billing.city=" . $getaddress->city ?? 'Jaipur';
        $data .= "&billing.state=" . $getaddress->state ?? 'Rajasthan';
        $data .= "&billing.country=" . $getaddress->iso_code_2 ?? 'IN';
        $data .= "&billing.postcode=" . $getaddress->postcode ?? '332012';
        $data .= "&customer.givenName=" . $getUser->name ?? 'Test';
        $data .= "&customer.surname=" . $getUser->name ?? 'Test'
        ;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjOWE0Yzk3ZGJkNGFlMzAxN2RjMzE1N2NlNzYyY2Z8S3lSZTlZaHJqRA=='));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);

        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        // dd(json_decode($responseData));
        $data = json_decode($responseData)->id;
        $address_id = $address_id;

        $pay_price = $order_price;

        session()->forget('coupan_code');
        $global_settings = DB::table('global_settings')->select('*')->first();
        $codCountries = DB::table('cod_countries')->where('global_id', $global_settings->id)->pluck('COD_Country_Name')->toArray();
        $addressCodExist = false;
        foreach ($codCountries as $key => $value) {
            $addressCodcheckExist = Address::where('id', Session::get('address_id'))->where('fulladdress', 'LIKE', "%{$value}%")->first();
            if ($addressCodcheckExist != null) {
                $addressCodExist = true;
            }
        }
        return view('frontend.checkout-payment-by-hyperpay', compact('addressCodExist', 'walletLessAmount', 'discounted_price', 'pay_price', 'entityId', 'types', 'data', 'method', 'address', 'address_id', 'order_price', 'ship_charge', 'delivery_charge', 'order_price_subtotal', 'wallet'));
    }

/**
 *
 * Web View Working
 */
    public function redirectHyperPage($id, $entityId, $user_id, $address_id, $method)
    {
        return view('frontend.webview.hyperpage', compact('id', 'entityId', 'user_id', 'address_id', 'method'));
    }
    public function hyperPayment(Request $request, $id, $entry_id, $user_id, $address_id)
    {
        $url = "https://eu-prod.oppwa.com/v1/checkouts/" . $id . "/payment";
        // $url = "https://test.oppwa.com/v1/checkouts/" . $id . "/payment";
        $url .= "?entityId=" . $entry_id;
        // test token : OGFjN2E0Yzg3ZDBlYTA3NDAxN2QwZjBhYjgxMDAxMWV8WGhzalJONzZuag;
        // live token : OGFjOWE0Yzk3ZGJkNGFlMzAxN2RjMzE1N2NlNzYyY2Z8S3lSZTlZaHJqRA;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjOWE0Yzk3ZGJkNGFlMzAxN2RjMzE1N2NlNzYyY2Z8S3lSZTlZaHJqRA=='));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $payment_status = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);

        $payment_status = json_decode($payment_status);
        if ($payment_status->result->code == "000.000.000") {
            $url = "https://eu-prod.oppwa.com/v1/payments/" . $payment_status->id; //live
            // $url = "https://test.oppwa.com/v1/payments/" . $payment_status->id;
            $data = "entityId=" . $entry_id .
            "&amount=" . $payment_status->amount .
            "&currency=" . $payment_status->currency .
                "&paymentType=DB";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization:Bearer OGFjOWE0Yzk3ZGJkNGFlMzAxN2RjMzE1N2NlNzYyY2Z8S3lSZTlZaHJqRA=='));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $payment_capture = curl_exec($ch);
            if (curl_errno($ch)) {
                return curl_error($ch);
            }
            curl_close($ch);
            $payment_capture = json_decode($payment_capture);
            $global = Global_settings::all();
            $useremail = User::where('id', $user_id)->first();
            $customer_id = $useremail->id;

            $coupon = Coupon_applied::where('user_id', $customer_id)->first();

            if (!empty($coupon)) {
                $promocode = Coupon::where('id', $coupon->coupon_id)->first();

                $coupon_history = new Coupon_history;
                $coupon_history->coupan_id = $coupon->coupon_id;
                $coupon_history->status = 1;
                $coupon_history->user_id = $customer_id;
                $coupon_history->save();
            }

            $customer_cart = Cart::where('user_id', $customer_id)->get();

            $orderprice = 0;
            foreach ($customer_cart as $value) {
                $product = Product::where('id', $value->product_id)->first();

                if ($value->quantity > $product->quantity) {
                    $res['error']['message'] = "Product is out of stock";
                    return response($res);
                }

                if (!empty($value->option_id)) {
                    $product_detail = Product_details::where('id', $value->option_id)->first();

                    if ($value->quantity > $product_detail->quantity) {
                        $res['error']['message'] = "Product is out of stock";
                        return response($res);
                    }

                    $orderprice = $orderprice + ($product_detail->price + $product->price) * $value->quantity;
                } else {
                    $orderprice = $orderprice + ($product->price) * $value->quantity;
                }
            }
            $product_sub_total = $orderprice;

            //Ship charge
            if ($orderprice > $global[0]->min_amount_shipping) {
                $orderprice = $orderprice;
                $ship_charge = 0;
            } else {
                $orderprice = $orderprice + $global[0]->shipping_charge;
                $ship_charge = $global[0]->shipping_charge;
            }
            //Delivery charge
            $delivery_charge = 0;
            //coupon code applied

            if (!empty($coupon) && !empty($promocode)) {
                $discount = ($orderprice / 100) * $promocode->discount;
                $orderprice = number_format($orderprice - $discount, 2);
            }

            //Quantity Decrease
            foreach ($customer_cart as $value) {
                $product = Product::where('id', $value->product_id)->first();
                $product_detail = Product_details::where('id', $value->option_id)->first();

                Product::where('id', $value->product_id)->update(['quantity' => $product->quantity - $value->quantity]);

                if (!empty($value->option_id)) {
                    Product_details::where('id', $value->option_id)->update(['quantity' => $product_detail->quantity - $value->quantity]);
                }
            }

            // Wallet manage

            $wallet = Wallet::where('user_id', $customer_id)->first();
            $walletamount = $payment_status->amount;
            if (!empty($wallet)) {
                if ($walletamount >= $orderprice) {
                    Wallet::where('user_id', $customer_id)->update(['amount' => $wallet->amount - $orderprice]);
                    $walletamount = $orderprice;

                    $history = new Wallet_recharge_history;
                    $history->amount = $walletamount;
                    $history->user_id = $customer_id;
                    $history->type = 2;
                    $history->reason = "Order";
                    $history->reason_ar = "ترتيب";
                    $history->save();

                } else {
                    Wallet::where('user_id', $customer_id)->update(['amount' => $wallet->amount - $payment_status->amount]);
                    $walletamount = $payment_status->amount;
                    $history = new Wallet_recharge_history;
                    $history->amount = $walletamount;
                    $history->user_id = $customer_id;
                    $history->type = 2;
                    $history->reason = "Order";
                    $history->reason_ar = "ترتيب";
                    $history->save();
                }
            } else {
                $walletamount = 0;
            }

            //Order Create

            $order = new Order;
            $order->payment_type = $payment_status->paymentType;
            $order->shipping_price = $ship_charge;
            $order->delivery_price = $delivery_charge;
            if (!empty($coupon)) {
                $order->coupan_id = $coupon->coupon_id;
            }
            $order->discount = $orderprice - ($product_sub_total + $ship_charge + $delivery_charge);
            $order->product_total_amount = $product_sub_total;
            $order->paid_by_wallet = $walletamount;
            $order->user_id = $customer_id;
            $order->address_id = $address_id;
            $order->price = $orderprice;
            $order->save();

            $ordertrack = new Order_track;
            $ordertrack->order_id = $order->id;
            $ordertrack->save();

            foreach ($customer_cart as $record) {
                $product = Product::where('id', $record->product_id)->first();
                $order_details = new Order_details;
                $order_details->order_id = $order->id;
                $order_details->user_id = $customer_id;
                $order_details->product_id = $record->product_id;
                if (!empty($record->option_id)) {
                    $product_detail = Product_details::where('id', $value->option_id)->first();

                    $order_details->color = $record->option_id;
                    $order_details->price = $product_detail->price + $product->price;
                } else {
                    $order_details->color = 'nocolor';
                    $order_details->price = $product->price;
                }
                $order_details->quantity = $record->quantity;
                $order_details->product_name_en = $product->name_en;
                $order_details->product_name_ar = $product->name_ar;

                $order_details->save();
            }

            $userdata = User::where('id', $customer_id)->first();
            $name = $userdata->name;

            $Device_token = $userdata->device_token;
            $user_id = $userdata->id;
            $msg = array(
                'body' => "Your Order #" . $order->id . " is Processing",
                'title' => 'Order Info',
                'subtitle' => 'Letsbuy',
                'key' => '5',
                'vibrate' => 1,
                'sound' => 1,
                'largeIcon' => 'large_icon',
                'smallIcon' => 'small_icon',
            );
            $this->Notificationsend($Device_token, $msg, $user_id);

            $EmailTemplates = Emailtemplate::where('slug', 'order_completed')->first();
            $message = str_replace(array('{name}'), array($name), $EmailTemplates->description_en);
            $subject = $EmailTemplates->subject_en;
            $to_email = $userdata->email;
            $data = array();
            $data['msg'] = $message;
            // Mail::send('emails.emailtemplate', $data, function ($message) use ($to_email, $subject) {
            //     $message->to($to_email)
            //         ->subject($subject);
            //     $message->from(env('MAIL_USERNAME', 'yallacashdubai@gmail.com'));
            //     // $message->from(env('MAIL_USERNAME', 'letsbuysa1@gmail.com'));
            // });

            $user_mobile = User::select('mobile')->where('id', $customer_id)->first();

            $mobile = $user_mobile->mobile;

            $user = "letsbuy";
            $password = "Nn0450292**";
            $mobilenumbers = $mobile;
            if ($request->header('language') == 3) {
                $message = 'Your Order is Successfully Placed. Order No ' . $order->id;
            } else {
                $message = ' تم تسجيل طلبك لدينا بنجاح . رقم الطلب ' . $order->id;
            }
            $senderid = "LetsBuy"; //Your senderid
            $message = urlencode($message);
            $url = "https://www.enjazsms.com/api/sendsms.php?username=" . $user . "&password=" . $password . "&message=" . $message . "&numbers=" . $mobilenumbers . "&sender=LetsBuy&unicode=E&return=null&port=1";
            // create a new cURL resource
            $ch = curl_init();
            // set URL and other appropriate options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // grab URL and pass it to the browser
            // close cURL resource, and free up system resources
            $curlresponse = curl_exec($ch);
            curl_close($ch);

            if (!empty($payment_status)) {
                $transaction = new Transaction_details;
                $transaction->charge_id = $payment_status->id;
                $transaction->payment_status = $payment_status->result->description;
                $transaction->user_id = Auth::id();
                $transaction->order_id = $order->id;
                $transaction->amount = $payment_status->amount;
                $transaction->currency = $payment_status->currency;
                $transaction->track_id = $payment_status->id;
                $transaction->payment_id = $payment_status->id;
                $transaction->transaction_generate_id = $payment_status->id;
                $transaction->order_generate_id = $payment_status->resultDetails->ConnectorTxID1;
                $transaction->receipt_id = $payment_status->resultDetails->ConnectorTxID1;
                $transaction->payment_method = $payment_status->paymentBrand;
                $transaction->payment_type = $payment_status->paymentType;
                $transaction->token_id = $payment_capture->referencedId;
                $transaction->save();
            }

            $getaddress = Address::where('id', $address_id)->first();
            Cart::where('user_id', $customer_id)->delete();

            $coupon = Coupon_applied::where('user_id', $customer_id)->first();
            if (!empty($coupon)) {
                Coupon::where('id', $coupon->coupon_id)->delete();
            }

            if ($request->header('language') == 3) {
                $res['success']['message'] = 'Order Placed Successfully';
                $res['order_id'] = $order->id;
                $res['total'] = $order->price;
                $res['fulladdress'] = $getaddress->fulladdress;
            } else {
                $res['success']['message'] = 'تم تقديم الطلب بنجاح';
                $res['order_id'] = $order->id;
                $res['total'] = $order->price;
                $res['fulladdress'] = $getaddress->fulladdress;
            }
            return redirect('payment/success/' . $payment_status->id . '/' . '1');
            // return response($payment_capture);
        } else {
            if ($payment_status->result->code == "200.300.404") {
                return redirect('payment/success/0/' . '0');
            }
            if ($payment_status->result->code == "800.120.100") {
                return redirect('payment/success/0/' . '0');
            } else {
                return redirect('payment/success/0/' . '0');
            }
        }
    }

    public function Notificationsend($Device_token, $msg, $user_id)
    {

        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'to' => $Device_token,
            'data' => $msg,
            'notification' => $msg,

        );
        // Firebase API Key
        $headers = array('Authorization:key=AAAAxF6P2ow:APA91bGuVr8cLvhZxVnB-8Ynv0y_k3RNJeHun3FcSJmONmmgJLOj-PTV2tamEojpmyg3jSn9JlDQqIa2zmYCUV6_aFnW5pkNhaNsaQcN8PWaAfGQFjl4TttzMzdWCongBqiWwHbGEPH9', 'Content-Type:application/json');
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === false) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);

        $notification = new Notification;
        $notification->user_id = $user_id;
        $notification->message = $msg['body'];
        $notification->message_type = $msg['key'];
        $notification->save();

    }

    public function successPage($id, $success)
    {
        return view('frontend.webview.success', compact('id', 'success'));
    }

    public function walletPayStore(Request $request)
    {
        Session::put('wallet', $request->wallet);
        return response()->json('success', 200);
    }

    // hyper web view
    public function hyperPayWallet($id, $entityId, $user_id, $method, $amount)
    {
        return view('frontend.webview.hyperpage-wallet', compact('id', 'entityId', 'user_id', 'method', 'amount'));
    }

    public function hyperPayWalletPayment(Request $request, $id, $entry_id, $user_id, $amount)
    {
        $url = "https://eu-prod.oppwa.com/v1/checkouts/" . $id . "/payment";
        // $url = "https://test.oppwa.com/v1/checkouts/" . $id . "/payment";
        $url .= "?entityId=" . $entry_id;
        // test token : OGFjN2E0Yzg3ZDBlYTA3NDAxN2QwZjBhYjgxMDAxMWV8WGhzalJONzZuag;
        // live token : OGFjOWE0Yzk3ZGJkNGFlMzAxN2RjMzE1N2NlNzYyY2Z8S3lSZTlZaHJqRA;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjOWE0Yzk3ZGJkNGFlMzAxN2RjMzE1N2NlNzYyY2Z8S3lSZTlZaHJqRA=='));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $payment_status = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        $payment_status = json_decode($payment_status);
        Log::debug((array) $payment_status);
        if ($payment_status->result->code == "000.000.000") {
            $url = "https://eu-prod.oppwa.com/v1/payments/" . $payment_status->id; //live
            // $url = "https://test.oppwa.com/v1/payments/" . $payment_status->id;
            $data = "entityId=" . $entry_id .
            "&amount=" . $amount .
            "&currency=" . $payment_status->currency .
                "&paymentType=DB";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization:Bearer OGFjOWE0Yzk3ZGJkNGFlMzAxN2RjMzE1N2NlNzYyY2Z8S3lSZTlZaHJqRA=='));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $payment_capture = curl_exec($ch);
            if (curl_errno($ch)) {
                return curl_error($ch);
            }
            curl_close($ch);
            $payment_capture = json_decode($payment_capture);
            Log::debug((array) $payment_capture);
            $userdata = User::where('id', $request->user_id)->first();
            $Device_token = $userdata->device_token;
            $user_id = $userdata->id;
            $msg = array(
                'body' => "Your Recharge is Successfully Completed",
                'title' => 'Recharge Info',
                'subtitle' => 'Letsbuy',
                'key' => '5',
                'vibrate' => 1,
                'sound' => 1,
                'largeIcon' => 'large_icon',
                'smallIcon' => 'small_icon',
            );
            $this->Notificationsend($Device_token, $msg, $user_id);
            $name = $userdata->name;
            $EmailTemplates = Emailtemplate::where('slug', 'recharge_completed')->first();
            $message = str_replace(array('{name}'), array($name), $EmailTemplates->description_en);
            $subject = $EmailTemplates->subject_en;
            $to_email = $userdata->email;
            $data = array();
            $data['msg'] = $message;
            Mail::send('emails.emailtemplate', $data, function ($message) use ($to_email, $subject) {
                $message->to($to_email)
                    ->subject($subject);
                $message->from(env('MAIL_USERNAME', 'letsbuysa1@gmail.com'));
            });

            $user_mobile = User::select('mobile')->where('id', $user_id)->first();

            $mobile = $user_mobile->mobile;

            $user = "letsbuy";
            $password = "Nn0450292**";
            $mobilenumbers = $mobile;
            if ((\App::getLocale() == 'en')) {
                $message = 'Your Wallet Recharege is Successfully Completed Amount :' . $amount;
            } else {
                $message = ' إعادة مشاركة المحفظة الخاصة بك قد اكتملت بنجاح ' . $amount;
            }
            $senderid = "LetsBuy"; //Your senderid
            $message = urlencode($message);
            $url = "https://www.enjazsms.com/api/sendsms.php?username=" . $user . "&password=" . $password . "&message=" . $message . "&numbers=" . $mobilenumbers . "&sender=LetsBuy&unicode=E&return=null&port=1";
            // create a new cURL resource
            $ch = curl_init();
            // set URL and other appropriate options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // grab URL and pass it to the browser
            // close cURL resource, and free up system resources
            $curlresponse = curl_exec($ch);
            curl_close($ch);

            $walletrecharge = new Wallet_recharge_history;
            $walletrecharge->amount = $amount;
            $walletrecharge->user_id = $user_id;
            $walletrecharge->type = 1;
            $walletrecharge->reason = "Recharge";
            $walletrecharge->reason_ar = "تعبئة رصيد";
            $walletrecharge->save();

            $walletamount = Wallet::where('user_id', $user_id)->first();
            if (!empty($walletamount)) {
                Wallet::where('user_id', $user_id)->update(['amount' => ((float) $amount) + ((float) $walletamount->amount)]);
            } else {
                $walletupdate = new Wallet;
                $walletupdate->amount = (float) $amount;
                $walletupdate->user_id = $user_id;
                $walletupdate->save();
            }

            $transaction = new Transaction_details;
            $transaction->transaction_generate_id = $payment_status->id;
            $transaction->is_applepay = 1;
            $transaction->save();

            if ($request->header('language') == 3) {
                $res['success']['message'] = 'Recharge Completed Successfully';
            } else {
                $res['success']['message'] = 'اكتملت عملية الشحن بنجاح';
            }
            return redirect('payment/success/0/' . '1');
        } else {
            if ($payment_status->result->code == "200.300.404") {
                return redirect('payment/success/0/' . '0');
            }
            if ($payment_status->result->code == "800.120.100") {
                return redirect('payment/success/0/' . '0');

            } else {
                return redirect('payment/success/0/' . '0');
            }
            return redirect('payment/success/0/' . '0');
        }
    }

}
