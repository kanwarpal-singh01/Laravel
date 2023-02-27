<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Coupon;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class CouponController extends Controller
{
    public function list(){
        $layout_data = [
            'title' => 'Promo Codes',
            'url' => route('admin.coupon.list'),
            'icon' => 'fa fa-bell'
        ];
        $list = Coupon::orderBy('created_at', 'desc')->paginate(config('custom-paginate.paginate.number'));;
        return view('admin.coupon.list', compact('layout_data','list'));
    }

    public function add(Request $request){
        $layout_data = [
            'title' => 'Promo Codes',
            'url' => route('admin.coupon.list'),
            'icon' => 'fa fa-bell'
        ];
        if ($request->isMethod('post')){
            $request->validate([
                'promo_code' => 'required|string|max:255|unique:coupons',
                'discount' => 'required|numeric|min:1',
                'start_date' => 'required',
                'end_date' => 'required',
                'months' => 'required|numeric|min:1',
                'status' => 'required|in:0,1',
            ]);
            //add coupons by stripe
            $stripe = new StripeClient(
                env('STRIPE_SECRET')
            );
            $coupons = $stripe->coupons->create([
                'percent_off' => $request['discount'],
                'duration' => 'repeating',
                'name' => $request['promo_code'],
                'duration_in_months' => $request['months'],
            ]);
            $add = new Coupon();
            $add->promo_code = $request['promo_code'];
            $add->discount = $request['discount'];
            $add->start_date = $request['start_date'];
            $add->end_date = $request['end_date'];
            $add->months = $request['months'];
            $add->status = $request['status'];
            $add->stripe_coupon_id = $coupons->id ?? '';
            $add->save();
            toastr()->success('Coupon successfully added!');
            return redirect()->route('admin.coupon.list');
        }
        return view('admin.coupon.add', compact('layout_data'));
    }
    public function edit(Request $request, $id, $page=null){
        $layout_data = [
            'title' => 'Promo Codes',
            'url' => route('admin.coupon.list'),
            'icon' => 'fa fa-bell'
        ];
        $edit = Coupon::findOrFail(base64_decode($id));
        if($request->isMethod('post')) {
            $request->validate([
                'promo_code' => 'required|string|max:255|unique:coupons,promo_code,'.base64_decode($id),
                'discount' => 'required|numeric',
                'start_date' => 'required',
                'end_date' => 'required',
                'months' => 'required|numeric',
                'status' => 'required|in:0,1',
            ]);
            $edit->promo_code = $request['promo_code'];
            $edit->discount = $request['discount'];
            $edit->start_date = $request['start_date'];
            $edit->end_date = $request['end_date'];
            $edit->months = $request['months'];
            $edit->status = $request['status'];
            $edit->save();
            toastr()->success('Coupon successfully updated!');
            return redirect('Kobe/coupon/list?page='.$page);
        }
        return view('admin.coupon.edit', compact('layout_data', 'edit'));
    }
    public function delete(Request $request, $id){
        $delete = Coupon::findOrFail(base64_decode($id));
        if ($delete){
            //delete coupons by stripe
            $stripe = new StripeClient(
                env('STRIPE_SECRET')
            );
            $stripe->coupons->delete(
                $delete->stripe_coupon_id
            );
            $delete->delete();
            toastr()->success('Coupon successfully deleted!');
            return redirect()->back();
        }
        toastr()->error('Coupon not deleted!');
        return redirect()->back();
    }
}
