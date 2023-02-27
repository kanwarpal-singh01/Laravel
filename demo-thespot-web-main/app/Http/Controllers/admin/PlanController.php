<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Mail\RegistrationMail;
use App\Mail\ActivationMail;
use App\Mail\DeactivationMail;
use App\Models\Contactus;
use Session;
use Helper;
use Hash;
use App\Models\EmailTemplate;
use Mail;
use App\Models\Plan;

class PlanController extends Controller
{
	//show plans with filter
    public function userList(Request $request){
    	$url = $request->segments();
    	$role = $request->input('role');
    	$query = $request->input('q');

    	$model = Plan::where(['role'=>$role]);
	    if ($query)
	    {
	      	$model = $model->where(function($q) use ($query)
	                      {
	                        $q->orwhere('title', 'LIKE', "%".$query."%");

	                      });
	    }
	    //echo $request->input('filter_by');die;
	    $filter_by = $request->input('filter_by') ;
    	switch ($request->input('filter_by')) {
    		case 'desc':
    			$model = $model->orderBy("id","desc");
    			break;
    		case 'asc':
    			$model = $model->orderBy("id","asc");
    			break;
    		case 'name':
    			$model = $model->orderByRaw('CONCAT(user_name)','asc');
    			break;
    		default:
    			$filter_by = "desc";
    			$model = $model->orderBy("id","desc");
    			break;
		}



    	$model = $model->paginate(config('Settings.AdminPageLimit'));

	    return view('admin/plans.index',  array('model' => $model,'query' => $query,'sort'=>$filter_by,'title' => 'Admin Display',));
	}

	//edit user
	public function edit(Request $request, $id){
		$method = $request->method();
		$url = $request->segments();
		if($method == 'PATCH')
		{
			$this->validate($request,[
				'title' => 'required|max:55',

			]);

			$input = $request->all();


			$existEmail = Plan::where('id','<>',base64_decode($id))->first();
			$record = Plan::find(base64_decode($id));

			if($record)
			{

				$record->fill($input)->save();
				//Plan::where('id',$record->id)->update(array('status'=> $input['status']));
				$record->save();

				Session::flash('message',$url[1].' updated successfully');
			}
			else
			{
				Session::flash('error','Invalid Id');
			}
			return redirect('admin/'.$url[1].'?role='.$record->role);

		}
		else
		{
			$record = Plan::find(base64_decode($id));
	    	return view('admin/plans.edit', array('record' => $record, 'title' => 'Edit Plans'));
	    }
	}

	//delete user
	public function delete($id,$name) {
        $record = Plan::find(base64_decode($id));
        $name=base64_decode($name);
    		$record->delete();
    		return redirect('admin/'.$name)->with('message', 'Plan Deleted successfully');
  	}



	//change status of user
	public function change_status(Request $request, $status, $id){
		$url = $request->segments();
		$matchText = ['id' => base64_decode($id)];
		$user = Plan::where($matchText)->first();
		if($user){
			if($status == 'activate'){
				$user->status = 1;

				Plan::where('id',base64_decode($id))->update(array('status'=>1));
			}
			if($status == 'deactivate'){
				$user->status = 0;

				Plan::where('id',base64_decode($id))->update(array('status'=>0));
			}
			$user->save();
			Session::flash('message','Status updated successfully!');

		}else{
			Session::flash('error','Invalid id');
		}
		return back();
	}







    //show form for create new static page
    public function create() {

		  return view('admin/plans.create', array('title' => 'Add plans'));
    }
    //eND USER REGISTER
    public function store(Request $request,$role){

        if($role != null && ($role == 'employer' || $role == 'recruiter')){

          if($request->method() == 'POST')
          {
                 $this->validate($request, [
                  'title' => 'required|max:55',

               ]);

			   $input =  $request->all();
			   $input['role']=$role;
               $register = Plan::create($input);
               if(isset($register->id) && !empty($register->id))
               {

				return redirect()->to('admin/'.$role.'-plan?role='.$role);

               }
               Session::flash('error', 'Internal server error');
               return redirect()->to('admin/'.$role);
          }
          return view('admin/plans.create');
        }
        return redirect('admin/'.$role.'-plan?role='.$role);
    }
}
