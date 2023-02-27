<?php

namespace App\Http\Controllers\Api;

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

class UsersController extends Controller
{
	//show users with filter
	public function userList(Request $request)
	{
		$query = $request->input('q');
		$status = $request->input('tus');
		$model = User::with('userDetail')->where('role', '!=', 'admin');
		if ($query) {
			$model = $model->where(function ($q) use ($query) {
				$q->orwhere('email', 'LIKE', "%" . $query . "%")
					->orwhere('name', 'LIKE', "%" . $query . "%");
			});
		}
		if (!empty($status)) {
			$status = base64_decode($status);
			switch ($status) {
				case 'active':
					$model = $model->where(['status'=>1]);
					break;
				case 'Not-Verified':
					$model = $model->where(['status'=>0]);
					break;
				case 'de-active':
					$model = $model->where(['status'=>2]);
					break;

				default:
					# code...
					break;
			}
		}
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
		return view('admin/users.index',  array('model' => $model, 'query' => $query, 'sort' => $filter_by, 'title' => 'Admin Display', 'pagelimit'=> $pagelimit));
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
			$this->validate($request, [
				'name' => 'required|max:85',
				'email' => 'required|email|unique:users,email,' . ($id ? base64_decode($id) : ''),
				'profile_image' => 'image|mimes:jpeg,png,jpg|max:1024'
			]);

			$input = $request->all();
			$existEmail = User::with('userDetail')->where('email', $input['email'])->where('id', '=', base64_decode($id))->first();
			$record = User::find(base64_decode($id));
			if ($record) {
				if ($request->file()) {
					$file = $request->file('profile_image');
					if ($file) {
						if (file_exists(public_path() . '/uploads/user/' . $record->profile_image)) {
							@unlink(public_path() . '/uploads/user/' . $record->profile_image);
						}
						$destinationPath = 'uploads/user/';
						$extension = $request->file('profile_image')->getClientOriginalExtension();
						$filename = rand(11111, 99999) . '.' . $extension;
						$file->move($destinationPath, $filename);
						$input['profile_image'] = $filename;
					}
				}
				$input['dob'] = date('Y-m-d', strtotime($input['dob']));
				$record->userDetail->fill($input)->save();
				$record->fill($input)->save();

				Session::flash('message', $url[1] . ' updated successfully');
			} else {
				Session::flash('error', 'Invalid Id');
			}
			return redirect('admin/' . $url[1]);
		}
		return view('admin/users.edit', array('record' => $record, 'title' => 'Edit companies'));
	}

	//delete user
	public function delete($id)
	{
		$record = User::find(base64_decode($id));
		$record->delete();
		return back()->with('message', 'User Deleted successfully');
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
			$validator = Validator::make($request->all(), ['email' => 'required|max:255', 'mobile' => 'required|unique:users|max:15']);
			if ($validator->fails()) {
				$errors = $validator->errors()->messages();
				$errors = implode(',', array_column($errors, '0'));
				Session::flash('error', $errors);
				return redirect()->back()->withErrors($validator)->withInput();
			}
			$requestData = $request->all();
			$requestData['role'] = 'users';
			$requestData['status'] = '1';
			DB::beginTransaction(); //Start transaction!
			try {
				if ($request->file()) {
					$file = $request->file('images');
					if ($file) {
						foreach ($file as $key => $value) {


							$path = 'public/uploads/user/';
							$extension = $value->getClientOriginalExtension();
							pr($extension);die;
							/* if (!empty($userData->images) && !empty($requestData['urls'])) {
								$urls = explode(',', $userData->images);
								foreach ($urls as $key => $value) {
									if (!in_array($value, $requestData['urls'])) {
										unset($urls[$key]);
										if (file_exists($path . $value)) {
											@unlink($path . $value);
										}
									}
								}
							} */



							$destinationPath = 'uploads/user/';
							pr($value);die;
							$extension = $request->file('profile_image')->getClientOriginalExtension();
							$filename = rand(11111, 99999) . '.' . $extension;
							$file->move($destinationPath, $filename);
							if($key == 0){
								$input['profile_image'] = $filename;
							}
						}
						if (file_exists(public_path() . '/uploads/user/' . $record->profile_image)) {
							@unlink(public_path() . '/uploads/user/' . $record->profile_image);
						}
						$destinationPath = 'uploads/user/';
						$extension = $request->file('profile_image')->getClientOriginalExtension();
						$filename = rand(11111, 99999) . '.' . $extension;
						$file->move($destinationPath, $filename);
						$input['profile_image'] = $filename;
					}
				}
				die("ASDF");
				$user = User::create($requestData);
				$user = User::find($user->id)->userDetail()->create($requestData);
			} catch (\Exception $e) {
				//failed logic here
				pr($e->getMessage());
				DB::rollback();
			}
			DB::commit();
			Session::flash('success', 'User added successful');
			return redirect()->route('userList');
		} else {
			Session::flash('error', 'Something went wrong.');
		}

		return redirect()->route('newUser');
	}
}

