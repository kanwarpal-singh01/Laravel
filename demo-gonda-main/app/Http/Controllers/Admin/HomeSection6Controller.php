<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\GlobalSettingManagement;
use App\Model\HomeSection6;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class HomeSection6Controller extends Controller
{
    public function list(){
        $layout_data = [
            'title' => 'Subscription Plans',
            'url' => route('admin.home.section6.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $list = HomeSection6::paginate(config('custom-paginate.paginate.number'));;
        return view('admin.home-section6.list', compact('layout_data','list'));
    }

    public function add(Request $request){//dd($request->all());
        $layout_data = [
            'title' => 'Subscription Plans',
            'url' => route('admin.home.section6.list'),
            'icon' => 'fa fa-picture-o'
        ];
        //$global = GlobalSettingManagement::first();
        if ($request->isMethod('post')){
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'price' => 'required',
                'credit_points' => 'sometimes|nullable',
                'type_ios' => 'required|string',
                'type_android' => 'required|string',
                'status' => 'required|in:0,1'
            ]);
            $plan = new HomeSection6();
            $plan->status = $request->status;
            $plan->title = $request->title;
            $plan->price = $request->price;
            $plan->credit_points = $request->credit_points;
            $plan->content = $request['content'];
            $plan->type_ios = $request['type_ios'];
            $plan->type_android = $request['type_android'];
            $plan->save();
//            $stripe = new StripeClient(
//                $global->stripe_datil
//                //'sk_test_51J5Od1LuHYJfNo16gT9SMKMqv0r8ZZhwRnbTmlOk1V8ClNItf87VgKy7EkLXzPedJojiyqjmAhgGVl28Ir9seQGS00gRxoRp1K'
//            );
//            $stripe->plans->create([
//                'amount' => $plan->price,
//                'currency' => 'usd',
//                'interval' => 'month',
//                'product' => 'prod_JjDEmqy9M2lKjO',
//            ]);
            toastr()->success('Data successfully added!');
            return redirect()->route('admin.home.section6.list');
        }
        return view('admin.home-section6.add', compact('layout_data'));
    }

    public function edit(Request $request, $id, $page=null){
        $layout_data = [
            'title' => 'Subscription Plans',
            'url' => route('admin.home.section6.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $edit = HomeSection6::findOrFail(base64_decode($id));
        if($request->isMethod('post')) {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'price' => 'required|string',
                'credit_points' => 'sometimes|nullable',
                'status' => 'required|in:0,1',
                'type_ios' => 'required|string',
                'type_android' => 'required|string',
            ]);
//            $stripe = new StripeClient(
//                env('STRIPE_SECRET')
//            );
//            $stripe->prices->update(
//                $edit->stripe_plan_id
//            );

            $edit->status = $request->status;
            $edit->title = $request->title;
            $edit->price = $request->price;
            $edit->credit_points = $request->credit_points;
            $edit->content = $request['content'];
            $edit->type_ios = $request['type_ios'];
            $edit->type_android = $request['type_android'];
            $edit->save();

            toastr()->success('Data successfully updated!');
            return redirect('Kobe/plan-management/list?page='.$page);
        }
        return view('admin.home-section6.edit', compact('layout_data', 'edit'));
    }

    public function delete(Request $request, $id){
        $delete = HomeSection6::findOrFail(base64_decode($id));
        if ($delete){
            $delete->delete();
            toastr()->success('Data successfully deleted!');
            return redirect()->back();
        }
        toastr()->error('Data not deleted!');
        return redirect()->back();
    }

    public function view(Request $request, $id){
        $layout_data = [
            'title' => 'Subscription Plans',
            'url' => route('admin.home.section6.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $view = HomeSection6::findOrFail(base64_decode($id));
        return view('admin.home-section6.view', compact('view', 'layout_data'));
    }
}
