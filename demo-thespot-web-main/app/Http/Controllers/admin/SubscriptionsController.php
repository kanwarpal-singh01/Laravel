<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscriptions;
use View;
use Session;
use Validator;
use Helper;

class SubscriptionsController extends Controller
{
    public function index(){
        $Subscriptions = Subscriptions::paginate(10);
        return View::make('admin.subscriptions.index')->with(compact('Subscriptions'));
    }

    public function changePlanStatus(Request $request, $id, $status){
        $url = $request->segments();
        $matchText = ['id' => base64_decode($id)];
        $Subscriptions = Subscriptions::where($matchText)->first();
        if ($Subscriptions) {
            $Subscriptions->status = $status;
            $Subscriptions->save();
            Session::flash('message', 'Plan status updated successfully!');
        } else {
            Session::flash('error', 'Invalid id');
        }
        return back();
    }
    public function create(Request $request){
        if($request->isMethod('post')){
            $plan = Subscriptions::create($request->all());
            if (isset($plan->id) && !empty($plan->id)) {
                Session::flash('success', 'Subscription successfully created');
                return redirect()->route('subscriptions');
            }else{
                Session::flash('error', 'Internal server error');
                return back();
            }
        }
        return View::make('admin.subscriptions.create');
    }

    public function edit(Request $request, $id){
        $plan = Subscriptions::findOrFail(base64_decode($id))->first();
        if($plan){
            if($request->isMethod('post')){
                $validator = Validator::make($request->all(), [
                    'discount' => 'nullable|numeric|min:0|max:99',
                    'amount' => ["required","regex:/^\d{1,8}?(\.\d{1,2})?$/"]
                ]);
                if ($validator->fails()) {
                    $error = Helper::WebValidationSet($validator->errors());
                    if(!empty($error)){
                        Session::flash('error', $error);
                        return back();
                    }
                }
                $plan->Fill($request->all());
                $plan->save();
                if ($plan->save()) {
                    Session::flash('message', 'Subscription has been changed');
                    return redirect()->route('subscriptions');
                }else{
                    Session::flash('error', 'Internal server error');
                    return back();
                }
            }
            return View::make('admin.subscriptions.edit')->with(compact('plan'));
        }else{
            Session::flash('error', 'Something went wrong');
            return back();
        }
    }
    public function view(Request $request, $id)
    {
        $url = $request->segments();
        $matchThese = ['id' => base64_decode($id)];
        $subscriptionDetail = Subscriptions::where($matchThese)->first();
        return view('admin.subscriptions.view', array('subscriptionDetail' => $subscriptionDetail));
    }
}
