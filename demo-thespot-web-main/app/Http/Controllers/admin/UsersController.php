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
use Validator;
use Helper;
use Hash;
use App\Models\EmailTemplate;
use Mail;
use App\User;
use App\Models\UsersDetail;
use Carbon\Carbon;
use Intervention\Image\ImageManagerStatic as Image;
class UsersController extends Controller
{
	//show users with filter
	public function userList(Request $request)
	{
		$query = $request->input('q');
		$status = $request->input('tus');
		$gender = $request->input('gender');
		$nationality = $request->input('nationality');
		$age = $request->input('age');
		$model = User::with('userDetail')->where('role', '!=', 'admin');
		if ($query) {
			$model = $model->where(function ($q) use ($query) {
				$q->orwhere('email', 'LIKE', "%" . $query . "%")
					->orwhere('name', 'LIKE', "%" . $query . "%");
			});
		}
		if ($age) {
			$model = $model->whereBetween('age',['17',$age]);
		}
		if ($gender) {
			$model = $model->whereHas('userDetail', function ($q) use($gender) {
				$q->where('gender', '=', $gender);
			});
		}
		if ($nationality) {
			$model = $model->whereHas('userDetail', function ($q) use($nationality) {
				$q->where("nationality", "LIKE", "%".$nationality."%");
			});
		}
		
		if (!empty($status)) {
			$status_decode = base64_decode($status);
			switch ($status_decode) {
				case 'active':
					$model = $model->where(['status' => 1]);
					break;
				case 'Not-Verified':
					$model = $model->where(['status' => 0]);
					break;
				case 'de-active':
					$model = $model->where(['status' => 2]);
					break;
				case 'fake_user':
					$model = $model->where('role','fake_user');
					break;
				case 'users':
						$model = $model->where('role','users');
						break;
				case 'paid_users':
					$paid_user_ids = DB::table('transactions')->pluck('user_id');
					$model = $model->whereIn('id',$paid_user_ids);
					break;
				default:
					# code...
					break;
			}
		}
		//pr($model->toSql());
		//echo $request->input('filter_by');die;
		$filter_by = $request->input('filter_by');
		switch ($request->input('filter_by')) {
			case 'desc':
				$model = $model->orderBy("id", "desc");
				break;
			case 'asc':
				$model = $model->orderBy("id", "asc");
				break;
			case 'name':
				$model = $model->orderByRaw('CONCAT(name)', 'asc');
				break;
			default:
				$filter_by = "desc";
				$model = $model->orderBy("id", "desc");
				break;
		}
		
		$pagelimit = Helper::get_config('AdminPageLimit');
		$model = $model->paginate($pagelimit);

		$nationalities = DB::table('countries')->pluck('nationality','nationality')->toArray();


		return view('admin/users.index', compact('model','query','gender','nationality','pagelimit','status', 'age','nationalities'));
	}
	## Add User
	public function add(Request $request)
	{
		return view('admin/users.create');
	}
	//edit user
	public function edit(Request $request, $id)
	{
		$method = $request->method();
		$url = $request->segments();
		$record = User::with('userDetail')->findOrFail(base64_decode($id));
		if ($method == 'PATCH') {
			$validator = Validator::make($request->all(), [
				'name' => 'required|max:85',
				'email' => 'required|email',
				'profile_image' => 'image|mimes:jpeg,png,jpg|max:1024',
				'mobile' => 'required|max:15',
				'lat' => 'required',
				'lng'=>'required'
			]);
			if ($validator->fails()) {
				$errors = $validator->errors()->messages();
				$errors = implode(',', array_column($errors, '0'));
				Session::flash('error', $errors);
				return redirect()->back()->withErrors($validator)->withInput();
			}
			$input = $request->all();
			$existEmail = User::with('userDetail')->where('email', $input['email'])->where('id', '=', base64_decode($id))->first();
			$record = User::find(base64_decode($id));
			if ($record) {
				if (isset($input['dob']) && !empty($input['dob'])) {
					if (date_create($input['dob']) > date_create('')) {
						sendResponse('Dob should be less than current date', 0);
					}
					$input['age'] =  date_diff(date_create($input['dob']), date_create('today'))->y;
				}

				// if ($request->file()) {
				// 	$file = $request->file('images');
				// 	if ($file) {
				// 		$images = explode(',', $record->images);
				// 		foreach ($images as $key => $value) {
				// 			if (file_exists(public_path() . '/uploads/user/' . $value)) {
				// 				@unlink(public_path() . '/uploads/user/' . $value);
				// 			}
				// 		}
				// 		$images = [];
				// 		$destinationPath = 'public/uploads/user/';
				// 		foreach ($file as $key => $value) {
				// 			if ($key < 6) {

				// 				$img = Helper::uploadImages($value, $destinationPath);
				// 				if ($key == 0) {
				// 					$input['profile_image'] = $img;
				// 				}
				// 				$images[] = $img;
				// 			}
				// 		}
				// 		$input['images'] = implode(',', $images);
				// 	}
				// }
				$path = 'public/uploads/user/';
            $files = $request->file('images');
            if($request->hasFile('images'))
            {
                foreach ($files as $file) {
                    $image 		= 	$file;
                    $folderName = 	strtoupper(date('M'). date('Y'))."/";
                    $my_filename 	= $file->getClientOriginalName();
                    $extension 	=	$file->getClientOriginalExtension();
                    $profile		=	time().'_'. $my_filename;
                    $destinationPath = $path;
                    $resize_image = Image::make($file->getPathname());
                    $resize_image->resize( 500,500 , function($constraint){
                        $constraint->aspectRatio();
                    })->save($destinationPath . '/medium/' .  $profile);
                    if($file->move($destinationPath.'/', $profile)){
                    
                    }
                    $imageData['urls'][]= $profile;
                
                }
				$imageData['urls'] = array_filter($imageData['urls'], 'strlen');

            

            $error=false;
            foreach(  $imageData['urls']  as  $new_url){
                $image_url=config('app.profile_url').'/'.$new_url;

              //  $image_url='https://www.pngkey.com/png/detail/230-2301779_best-classified-apps-default-user-profile.png';
                $result=$this->image_checker($image_url);
                
                if($result['status']){
                    $error=true;
                }
               
            }
          
            if($error){

                foreach ($imageData['urls'] as $key => $value) {
                    if (file_exists($path.'/'. $value)) {
                        @unlink($path.'/' . $value);
                        // @unlink($path.'small/' . $value);
                        @unlink($path.'medium/' . $value);
                    //  @unlink($path.'large/' . $value);
                    } 
                 }
				 Session::flash('error', 'Your images has some nude or porn content available.');
				 return redirect()->back()->withInput();
               
            }


           
            $input['images'] = implode(',', $imageData['urls']);
            }
				$record->userDetail->fill($input)->save();
				$record->fill($input)->save();

				Session::flash('message', $url[1] . ' updated successfully');
			} else {
				Session::flash('error', 'Invalid Id');
			}
			return redirect('admin/' . $url[1]);
		}
		$matchThese = ['id' => base64_decode($id)];
		$userDetail = User::with('userDetail')->where($matchThese)->first();
		return view('admin/users.edit', array('record' => $record,'userDetail'=>$userDetail, 'title' => 'Edit companies'));
	}

	//delete user
	public function delete($id){
		DB::table('like_unlikes')->where('user_id', base64_decode($id))->orWhere('like_user_id',base64_decode($id))->delete();

		DB::table('chats')->where('sender_id', base64_decode($id))->orWhere('receiver_id',base64_decode($id))->delete();
		DB::table('user_chats')->where('sender_id', base64_decode($id))->orWhere('receiver_id',base64_decode($id))->delete();
		DB::table('user_chats_premium')->where('sender_id', base64_decode($id))->orWhere('receiver_id',base64_decode($id))->delete();
	
		User::where(['id' => base64_decode($id)])->delete();
		return back()->with('message', 'User Deleted successfully');
	}

	public function deleteMultipleUsers(Request $request){

		$user_ids = $request->all();
		$response	=	array();

		//echo '<pre>'; print_r($user_ids); die;
		if(!empty($user_ids)){
			DB::table('like_unlikes')->whereIn('like_user_id',$user_ids)->delete();
			DB::table('like_unlikes')->whereIn('user_id',$user_ids)->delete();
			DB::table('chats')->whereIn('sender_id',$user_ids)->delete();
			DB::table('chats')->whereIn('receiver_id',$user_ids)->delete();
			DB::table('user_chats')->whereIn('sender_id',  $user_ids)->delete();
			DB::table('user_chats')->whereIn('receiver_id',$user_ids)->delete();
			DB::table('user_chats_premium')->whereIn('receiver_id',$user_ids)->delete();
			DB::table('user_chats_premium')->whereIn('sender_id',$user_ids)->delete();
			User::whereIn('id',$user_ids)->delete();

			$response["status"] = "1";
			$response["messasge"] = "You have sucessfully submit";
		}else{

			$response["status"] = "2";
			$response["messasge"] = "Invalid Request";
		}
		



		return json_encode($response);

	}
	

	//view user
	public function view(Request $request, $id)
	{
		$url = $request->segments();
		$matchThese = ['id' => base64_decode($id)];
		$userDetail = User::with('userDetail')->where($matchThese)->first();
		//pr($userDetail);die;
		return view('admin/users.view', array('userDetail' => $userDetail));
	}

	//change status of user
	public function change_status(Request $request, $status, $id)
	{
		$url = $request->segments();
		$matchText = ['id' => base64_decode($id)];
		$user = User::where($matchText)->first();
		if ($user) {
			if ($status == 'activate') {
				$user->status = 1;
				//Mail::to($user->email)->send(new ActivationMail($user));
				User::where('id', base64_decode($id))->update(array('status' => 1));
			}
			if ($status == 'deactivate') {
				$user->status = 2;
				//Mail::to($user->email)->send(new DeactivationMail($user ));
				User::where('id', base64_decode($id))->update(array('status' => 0));
			}
			if ($status == 'suspended') {
				$user->status = 5;
				//Mail::to($user->email)->send(new DeactivationMail($user ));
				User::where('id', base64_decode($id))->update(array('status' => 5));
			}
			$user->save();
			Session::flash('message', 'Status updated successfully!');
		} else {
			Session::flash('error', 'Invalid id');
		}
		return back();
	}

	public function resendEmail($id)
	{
		$user = User::Where('id', base64_decode($id))->first();
		$emailtemplate = EmailTemplate::where('slug', 'user-registration')->first();
		$activationlink = url('/activate/') . '/' . base64_encode($user->id);

		if (isset($emailtemplate->content) && $emailtemplate->content != "") {
			$emailtemplate->content = str_replace(array('{name}', '{url}'), array($user->user_name, $activationlink), $emailtemplate->content_de);
		} else {
			$emailtemplate->content = str_replace(array('{name}', '{url}'), array($user->user_name, $activationlink), $emailtemplate->content);
		}
		$fromemail = config("Settings.AdminEmail");
		$email  = $user->email;
		Mail::send('emails.common', ['datacontent' => $emailtemplate->content], function ($m) use ($fromemail, $email, $emailtemplate) {
			$m->from($fromemail, 'Bagoo');
			$m->to($email)->subject($emailtemplate->subject);
		});

		return back()->with("message", "Account verification email sent successfully.");
	}
	public function contactus(Request $request)
	{
		$query = $request->input('q');
		if ($query) {
			$records = Contactus::orderBy('id', 'desc')->where('title', 'LIKE', "%$query%")->paginate(config('Settings.AdminPageLimit'));
			return view('admin/users.contactus', array('records' => $records, 'query' => $query));
		} else {
			$records = Contactus::orderBy('id', 'desc')->paginate(config('Settings.AdminPageLimit'));
			return view('admin/users.contactus',  array('records' => $records, 'title' => 'Job post'));
		}
	}

	public function send_mail(Request $request)
	{

		if ($request->isMethod('post')) {

			$content = $request->content;
			$email = $request->email;

			Mail::send('emails.contact', ['email' => $email, 'content' => $content], function ($message) use ($email) {
				$message->to($email);
				$message->subject("Mail from Bagoo");
			});
			return redirect('admin/contactus')->with('message', ' Mail Sended Successfully.');
		}
		return view('admin/users/contact_mail');
	}
	public function changeStatus(Request $request)
	{
		$records = Contactus::where(['id' => $request->user_id])->first();
		$records->status = $request->status;
		$records->save();
		Session::flash('message', 'Status updated successfully!');
		return response()->json(['success' => 'Status change successfully.']);
	}
	//show form for create new static page
	public function create()
	{
		return view('admin/users.create', array('title' => 'Add users'));
	}
	//eND USER REGISTER
	public function store(Request $request)
	{
		if ($request->isMethod('post')) {
			$validator = Validator::make($request->all(), ['email' => 'required|max:255', 'mobile' => 'required|unique:users|max:15','lat' => 'required',
			'lng'=>'required']);
			if ($validator->fails()) {
				$errors = $validator->errors()->messages();
				$errors = implode(',', array_column($errors, '0'));
				Session::flash('error', $errors);
				return redirect()->back()->withErrors($validator)->withInput();
			}
			$requestData = $request->all();
			$requestData['status'] = '1';
			DB::beginTransaction(); //Start transaction!
			try {

				if (isset($requestData['dob']) && !empty($requestData['dob'])) {
					if (date_create($requestData['dob']) > date_create('')) {
						Session::flash('error', "Age Should be less the 18 ");
						return redirect()->back()->withErrors($validator)->withInput();
					}
					$requestData['age'] =  date_diff(date_create($requestData['dob']), date_create('today'))->y;
				}
				// if ($request->file()) {
				// 	$file = $request->file('images');
				// 	if ($file) {
				// 		$images = [];
				// 		$destinationPath = 'public/uploads/user/';
				// 		foreach ($file as $key => $value) {
				// 			if ($key < 6) {

				// 				$img = Helper::uploadImages($value, $destinationPath);
				// 				if ($key == 0) {
				// 					$requestData['profile_image'] = $img;
				// 				}
				// 				$images[] = $img;
				// 			}
				// 		}
				// 		$requestData['images'] = implode(',', $images);
				// 	}
				// }
				$path = 'public/uploads/user/';
            $files = $request->file('images');
            if($request->hasFile('images'))
            {
                foreach ($files as $file) {
                    $image 		= 	$file;
                    $folderName = 	strtoupper(date('M'). date('Y'))."/";
                    $my_filename 	= $file->getClientOriginalName();
                    $extension 	=	$file->getClientOriginalExtension();
                    $profile		=	time().'_'. $my_filename;
                    $destinationPath = $path;
                    $resize_image = Image::make($file->getPathname());
                    $resize_image->resize( 500,500 , function($constraint){
                        $constraint->aspectRatio();
                    })->save($destinationPath . '/medium/' .  $profile);
                    if($file->move($destinationPath.'/', $profile)){
                    
                    }
                    $imageData['urls'][]= $profile;
                
                }
				$imageData['urls'] = array_filter($imageData['urls'], 'strlen');

            

				$error=false;
				foreach(  $imageData['urls']  as  $new_url){
					$image_url=config('app.profile_url').'/'.$new_url;
	
				  //  $image_url='https://www.pngkey.com/png/detail/230-2301779_best-classified-apps-default-user-profile.png';
					$result=$this->image_checker($image_url);
					
					if($result['status']){
						$error=true;
					}
				   
				}
			  
				if($error){
	
					foreach ($imageData['urls'] as $key => $value) {
						if (file_exists($path.'/'. $value)) {
							@unlink($path.'/' . $value);
							// @unlink($path.'small/' . $value);
							@unlink($path.'medium/' . $value);
						//  @unlink($path.'large/' . $value);
						} 
					 }
					 Session::flash('error', 'Your images has some nude or porn content available.');
					 return redirect()->back()->withInput();
				   
				}
	
	
				$requestData['profile_image'] = $imageData['urls'][0];
				$requestData['images'] = implode(',', $imageData['urls']);
            }

           
				$user = User::create($requestData);
				$user = User::find($user->id)->userDetail()->create($requestData);
			} catch (\Exception $e) {
				//failed logic here
				DB::rollback();
				Session::flash('error', $e->getMessage());
				return redirect()->back()->withErrors($validator)->withInput();
			}
			DB::commit();
			Session::flash('success', 'User added successful');
			return redirect()->route('userList');
		} else {
			Session::flash('error', 'Something went wrong.');
		}

		return redirect()->route('newUser');
	}
	public function image_checker($image_url){

      
		$ch = curl_init();
	
		$data = array('url_image' => $image_url,
				'API_KEY' => "vM31KnY96wG2XyJ2o0NWoDdsUzQ16edH",
				'task' => 'porn_moderation,gore_moderation',
				'origin_id' => "xxxxx",
				'reference_id' => "yyyyyy"
		
		);
		
		curl_setopt($ch, CURLOPT_URL,'https://www.picpurify.com/analyse/1.1');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
		curl_setopt($ch,CURLOPT_SAFE_UPLOAD,true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		
		$output = curl_exec($ch);
		
		$result=json_decode($output);
       
       

	if(isset($result->porn_moderation) && $result->porn_moderation->porn_content==true && $result->final_decision=='KO' ){ 
		return ['status'=>1];

	}else{
		
		return ['status'=>0];
	}
	
	
	}
}
