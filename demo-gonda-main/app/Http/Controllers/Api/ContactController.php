<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function add(Request $request){

        $validator = Validator::make($request->all(), [
//            'contact_name' => 'required|string|max:255',
//            'contact_mobile_number' => 'required|digits_between:8,12|unique:contacts,contact_mobile_number,'.auth('api')->id(),
//            'contact_mobile_number' => 'required|digits_between:8,12',
//            'contact_country_code' => 'required|digits_between:1,7',
//            'contact_image' => 'mimes:jpg,jpeg,png,svg|max:50000',
//            'user_id' => 'required|exists:users,id',
            "contacts" => 'required|array|min:1',
            'contacts.*.contact_first_name' => 'required|string|max:255',
            'contacts.*.contact_last_name' => 'sometimes|nullable|string|max:255',
            //'contact_mobile_number' => 'required|digits_between:8,12|unique:contacts,contact_mobile_number,'.auth('api')->id(),
            'contacts.*.contact_mobile_number' => 'required|string|min:5|max:255',
            'contacts.*.contact_country_code' => 'required|digits_between:1,7',
            //'contacts.*.contact_image' => 'mimes:jpg,jpeg,png,svg|max:50000',
        ]);
        if ($validator->fails()){
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }
        try {

//            $contact = Contact::where(['contact_mobile_number' => $request['contact_mobile_number'],
//                'user_id' => auth('api')->id()])->first();
//            if ($contact){
//                return response()->json([
//                    'status' => 1,
//                    'message' => 'Already contact added.',
//                    'contact_image_path' => URL::to('/') . '/public/storage/uploads/contact-image/',
//                    'data' => $contact
//                ]);
//            }
//            $contact = new Contact();
//            $contact->user_id = auth('api')->id();
//            $contact->contact_name = $request['contact_name'];
//            $contact->contact_mobile_number = $request['contact_mobile_number'];
//            $contact->contact_country_code = $request['contact_country_code'];
//            if ($request->hasFile('contact_image')) {
//                $file = $request->file('contact_image');
//                if ($file) {
//                    $destinationPath = 'public/storage/uploads/contact-image/';
//                    $extension = $request->file('contact_image')->getClientOriginalExtension();
//                    $filename =  time(). '.' . $extension;
//                    $file->move($destinationPath, $filename);
//                    $contact->contact_image = $filename;
//                }
//            }
//            $contact->save();


            $contacts = [];
            foreach ($request['contacts'] as $contact_data) {
                $contact = Contact::where(['contact_mobile_number' => $contact_data['contact_mobile_number'], 'user_id' => auth('api')->id()])->first();
                if ($contact){
                    $contacts[] = $contact;
                    $message = 'Contact already added.';
                    $status = 0;
                    continue;
                }
                $contact = new Contact();
                $contact->user_id = auth('api')->id();
                $contact->contact_first_name = $contact_data['contact_first_name'];
                $contact->contact_last_name = $contact_data['contact_last_name'] ?? '';
                $contact->contact_mobile_number = $contact_data['contact_mobile_number'];
                $contact->contact_country_code = $contact_data['contact_country_code'];
//                if (!empty($contact_data['contact_image'])) {
//                    //$file = $request->file('contact_image');
//                    $file = $contact_data['contact_image'];
//                    if ($file) {
//                        $destinationPath = 'public/storage/uploads/contact-image/';
//                        $extension = $contact_data['contact_image']->getClientOriginalExtension();
//                        $filename = time() . '.' . $extension;
//                        $file->move($destinationPath, $filename);
//                        $contact->contact_image = $filename;
//                    }
//                }
                $contact->save();
                $message = 'Contact successfully added.';
                $status = 1;
                $contacts[] = $contact;
            }
            return response()->json([
                'status' => $status,
                'message' => $message,
                'contact_image_path' => URL::to('/') . '/public/storage/uploads/contact-image/',
                'data' => $contacts
            ]);
        }
        catch (\Exception $exception){
            return error_response($exception);
        }
    }

    public function edit(Request $request){
        $validator = Validator::make($request->all(), [
            'contact_id' => 'required|exists:contacts,id',
            //'user_id' => 'required',
            'contact_first_name' => 'required|string|max:255',
            'contact_last_name' => 'required|string|max:255',
            'contact_mobile_number' => 'required|string|min:5|max:15|unique:contacts,id,'.auth('api')->id(),
            'contact_country_code' => 'required|digits_between:1,7',
            'contact_image' => 'mimes:jpg,jpeg,png,svg|max:50000',
        ]);
        if ($validator->fails()){
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }
        try{
            $contact = Contact::where(['id' => $request['contact_id'], 'user_id' => auth('api')->id()])->first();
            if (!empty($contact)) {
                $contact->user_id = auth('api')->id();
                $contact->contact_first_name = $request['contact_first_name'];
                $contact->contact_last_name = $request['contact_last_name'];
                $contact->contact_mobile_number = $request['contact_mobile_number'];
                $contact->contact_country_code = $request['contact_country_code'];
                if ($request->hasFile('contact_image')) {
                    $file = $request->file('contact_image');
                    if ($file) {
                        $destinationPath = 'public/storage/uploads/contact-image/';
                        $extension = $request->file('contact_image')->getClientOriginalExtension();
                        $filename = time() . '.' . $extension;
                        $file->move($destinationPath, $filename);
                        $contact->contact_image = $filename;
                    }
                }
                $contact->save();
                return response()->json([
                    'status' => 1,
                    'message' => 'Contact successfully updated.',
                    'contact_image_path' => URL::to('/') . '/public/storage/uploads/contact-image/',
                    'data' => $contact
                ]);
            }
            return response()->json([
                'status' => 1,
                'message' => 'No records',
                'data' => []
            ]);
        }
        catch (\Exception $exception){
            return error_response($exception);
        }

    }

    public function list(Request $request){
        try{
            $contact = Contact::query();

            if (!empty($request['search'])){
                $contact = $contact->where('contact_first_name', 'LIKE', '%'.$request['search'].'%');
                $contact = $contact->orWhere('contact_last_name', 'LIKE', '%'.$request['search'].'%');
                $contact = $contact->orWhere('contact_mobile_number', 'LIKE', '%'.$request['search'].'%');
            }
            $contact = $contact->where('user_id', auth('api')->id())->orderBy('contact_first_name', 'asc')->paginate(10);

            if ($contact->isNotEmpty()){
                return response()->json([
                    "status" => 1,
                    "message" => 'Contact List',
                    'contact_image_path' => URL::to('/') . '/public/storage/uploads/contact-image/',
                    "data" => $contact
                ]);
            }
            return response()->json([
                "status" => 1,
                "message" => 'No Records',
                "data" => []
            ]);
        }
        catch (\Exception $exception){
            return error_response($exception);
        }
    }

    public function delete(Request $request){
        $validator = Validator::make($request->all(), [
            'contact_id' => 'required|exists:contacts,id',
            //'user_id' => 'required',
        ]);
        if ($validator->fails()){
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }
        try{
            $contact_id = $request['contact_id'];//dd(auth('api')->id());

                $contact = Contact::where(['id' => $contact_id, 'user_id' => auth('api')->id()])->first();
                //dd($contact);
                if (!empty($contact)) {
                    $contact->delete();
                    return response()->json([
                        "status" => 1,
                        "message" => 'Contact deleted successfully.',
                        "data" => []
                    ]);
                }
                return response()->json([
                "status" => 1,
                "message" => 'No Records',
                "data" => []
            ]);
        }
        catch (\Exception $exception){
            return error_response($exception);
        }
    }

    public function importContact(Request $request){
        $validator = Validator::make($request->all(), [
            "contacts" => 'required|array|min:1',
            'contacts.*.contact_name' => 'required|string|max:255',
            'contacts.*.contact_mobile_number' => 'required|digits_between:8,12',
            'contacts.*.contact_country_code' => 'required|digits_between:1,7',
        ]);
        if ($validator->fails()){
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }
        try {
            $contacts = [];
            foreach ($request['contacts'] as $contact_data) {
                $contact = Contact::where(['contact_mobile_number' => $contact_data['contact_mobile_number'],
                                            'user_id' => auth('api')->id()])->first();
                if ($contact){
                    $contacts[] = $contact;
                    continue;
                }
                $contact = new Contact();
                $contact->user_id = auth('api')->id();
                $contact->contact_name = $contact_data['contact_name'];
                $contact->contact_mobile_number = $contact_data['contact_mobile_number'];
                $contact->contact_country_code = $contact_data['contact_country_code'];
                $contact->save();
                $contacts[] = $contact;
            }
            return response()->json([
                'status' => 1,
                'message' => 'Contact successfully added.',
                'data' => $contacts
            ]);
        }
        catch (\Exception $exception){
            return error_response($exception);
        }
    }
}
