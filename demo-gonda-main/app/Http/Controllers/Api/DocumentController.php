<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\CompanyDetail;
use App\Model\Contact;
use App\Model\Document;
use App\Model\EmailTemplateManagement;
use App\Model\GlobalSettingManagement;
use App\Model\HomeSection6;
use App\Model\Keyword;
use App\Model\MaillingAddress;
use App\Model\NotaryDetail;
use App\Model\Notification;
use App\Model\TemplateManagement;
use App\Model\Transaction;
use App\Model\UserTransaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Mpdf\Mpdf;
use Stripe\StripeClient;

class DocumentController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'contacts' => 'required|array',
            // 'contacts.*.contact_id' => 'required|exists:contacts,id',
            // 'contacts.*.document_id' => 'sometimes|nullable|exists:documents,id',
            // 'content' => 'sometimes|nullable|string',
            //'state_id' => 'required',
            // 'template' => 'required',
            // 'keywords' => 'required|array',
            // 'keywords.*.id' => 'required|exists:keywords,id',
            // 'keywords.*.answer' => 'required',
            // 'auto_sign' => 'required|in:0,1',
            // 'type' => 'required|in:1,2',
            'contacts' => 'required|array',
            'contacts.*.contact_id' => 'required|exists:contacts,id',
            'contacts.*.document_id' => 'sometimes|nullable|exists:documents,id',
            'content' => 'sometimes|nullable|string',
            //'state_id' => 'required',
            'template' => 'required_if:type,=,1',
            'keywords' => 'required_if:type,=,1|array',
            'keywords.*.id' => 'required_if:type,=,1|exists:keywords,id',
            'keywords.*.answer' => 'required_if:type,=,1',
            'auto_sign' => 'required|in:0,1',
            'type' => 'required|in:1,2',
        ], [
            'contact_id.*.exists' => 'The entered contact id does not exists.',
            'contact_id.*.unique' => 'The contact id has already been taken.',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }
        $document_data = [];
        $user_data = [];
        try {
            DB::beginTransaction();
            $user = auth('api')->user();
            $global_setting = GlobalSettingManagement::first();
            if (!empty($request['contacts'])) {
                $last_draft = Document::where('type', 2)->latest()->first('batch');
                $batch = !empty($last_draft) ? $last_draft->batch + 1 : 1;

                foreach ($request['contacts'] as $contact) {
                    if (!empty($contact['document_id'])) {
                        $document = Document::find($contact['document_id']);
                        $document->status = 1;
                        $document->type = 1;
                        $document->batch = 0;
                        $document->keyword_id = json_encode($request['keywords']);
                        $document->save();

                        if ($document->contact->userByMobileNumber) {
                            if ($document->contact->userByMobileNumber->push_notification == 1) {
                                //for push notification
                                $msg_rec = array(
                                    'body' => ucfirst($document->user->first_name) . " " . ucfirst($document->user->last_name) . " has sent you a document.",
                                    'title' => 'go NDA',
                                    'type' => 'document',
                                    'document' => $document->id,
                                    'subtitle' => ucfirst($document->user->first_name) . " " . ucfirst($document->user->last_name) . " has sent you a document.",
                                    'key' => '5',
                                    'vibrate' => 1,
                                    'sound' => 1,
                                    'largeIcon' => 'large_icon',
                                    'smallIcon' => 'small_icon'
                                );
                                push_notification_send_driver($document->contact->userByMobileNumber->device_token, $msg_rec);
                                //for push notification
                            }
                            if ($document->contact->userByMobileNumber->email_notification == 1) {

                                $url = Route('share', $document->id);
                                twilio_sms($document->contact->userByMobileNumber->country_code, $document->contact->userByMobileNumber->mobile_number, null, "Hi, its goNDA, " . ucfirst($document->user->first_name) . " " . ucfirst($document->user->last_name) . " has sent you a document (#" . $document->id . "). Click to view. / " . $url);
                            }
                        }

                        if ($document->user->push_notification == 1) {
                            $receiver_name = !empty($document->contact->userByMobileNumber) ? $document->contact->userByMobileNumber->first_name . " " . $document->contact->userByMobileNumber->last_name : $document->contact->contact_first_name . " " . $document->contact->contact_last_name;
                            //for push notification
                            $msg_send = array(
                                'body' => "Your document(#" . $document->id . ") has been sent to " . ucfirst($receiver_name),
                                'title' => 'go NDA',
                                'type' => 'document',
                                'document' => $document->id,
                                'subtitle' => "Your document(#" . $document->id . ") has been sent to " . ucfirst($receiver_name),
                                'key' => '5',
                                'vibrate' => 1,
                                'sound' => 1,
                                'largeIcon' => 'large_icon',
                                'smallIcon' => 'small_icon'
                            );
                            push_notification_send_driver($document->user->device_token, $msg_send);
                            //for push notification
                        }

                        $document_data[] = $document;
                    } else {
                        $document = new Document();
                        $document->user_id = auth('api')->id();
                        $document->contact_id = $contact['contact_id'];
                        $document->content = $request['content'] ?? null;
                        $document->state_id = $request['state_id'] ?? null;
                        $document->template = $request['template'] ?? null;
                        $document->auto_sign = $request['auto_sign'];
                        $document->sender_sign = $request['auto_sign'] == 1 ? $user->sign_image : null;
                        $document->type = $request['type'];
                        $document->status = $request['type'] == 1 ? 1 : 0;
                        $document->batch = $request['type'] == 1 ? 0 : $batch;
                        $document->keyword_id = json_encode($request['keywords']);
                        $document->save();
                        //                        if ($document->contact) {
                        //                            $receiver_user_id = $document->contact->userByMobileNumber->id ?? null;
                        //                            $sender_contact_number = $user->mobile_number;
                        //                            if($receiver_user_id) {
                        //                                $receiver_contact_id = Contact::where([
                        //                                    'user_id' => $receiver_user_id,
                        //                                    'contact_mobile_number' => $sender_contact_number
                        //                                ])->first();
                        //                                $document->receiver_contact_id = $receiver_contact_id->id;
                        //                                $document->save();
                        //                            }
                        //                        }
                        // update sender and receiver details
                        // if($document->user){
                        //   $sender_company_details = CompanyDetail::where('user_id', $document->user->id)->first();
                        //   $sender_mailling_address = MaillingAddress::where('user_id', $document->user->id)->first();
                        //   $sender_data = $document->user.$sender_company_details.$sender_mailling_address;
                        //   $document->sender_details = json_encode($sender_data);
                        //   $document->save();
                        // }
                        // if($document->contact->userByMobileNumber){
                        //     $receiver_company_details = CompanyDetail::where('user_id', $document->contact->userByMobileNumber->id)->first();
                        //     $receiver_mailling_address = MaillingAddress::where('user_id', $document->contact->userByMobileNumber->id)->first();
                        //     $receiver_data = $document->contact->userByMobileNumber.$receiver_company_details.$receiver_mailling_address;
                        //     $document->receiver_details = json_encode($receiver_data);
                        //     $document->save();
                        // }
                        $document->sender_details = json_encode($document->user);
                        $document->receiver_details = json_encode($document->contact->userByMobileNumber);
                        $document->save();

                        //                        if ($document->user->email_notification == 1) {
                        //                            $notification_to_sender = new Notification();
                        //                            //$notification_to_sender->comment = "You have sent document(#" . $document->id . ") to " . ucfirst($document->contact->contact_first_name) . " " . ucfirst($document->contact->contact_last_name);
                        //                            $notification_to_sender->comment = "Your document(#".$document->id.") has been sent to ".ucfirst($document->contact->contact_first_name) . " " . ucfirst($document->contact->contact_last_name);
                        //                            $notification_to_sender->type = 'document';
                        //                            $notification_to_sender->document_id = $document->id;
                        //                            $notification_to_sender->user_id = $document->user_id;
                        //                            $notification_to_sender->save();
                        //                        }
                        if ($document->contact->userByMobileNumber) {

                            $notification_to_receiver = new Notification();
                            //$notification_to_receiver->comment = "You have received document(#" . $document->id . ") to sign from " . ucfirst($document->user->first_name) . " " . ucfirst($document->user->last_name);
                            $notification_to_receiver->comment = ucfirst($document->user->first_name) . " " . ucfirst($document->user->last_name) . " has sent you a document.";
                            $notification_to_receiver->type = 'document';
                            $notification_to_receiver->document_id = $document->id;
                            $notification_to_receiver->user_id = $document->contact->userByMobileNumber->id ?? null;
                            $notification_to_receiver->save();


                            //dd($document->contact->userByMobileNumber->country_code, $document->contact->userByMobileNumber->mobile_number);    
                            if ($document->contact->userByMobileNumber->email_notification == 1) {

                                $url = Route('share', $document->id);
                                twilio_sms($document->contact->userByMobileNumber->country_code, $document->contact->userByMobileNumber->mobile_number, null, "Hi, its goNDA, " . ucfirst($document->user->first_name) . " " . ucfirst($document->user->last_name) . " has sent you a document(#" . $document->id . "). Click to view. / " . $url);
                            }
                        }
                        if ($document->type == 1) {

                            if ($document->contact->userByMobileNumber) {
                                if ($document->contact->userByMobileNumber->push_notification == 1) {
                                    //for push notification
                                    $msg_rec = array(
                                        //'body' => "You have received document(#" . $document->id . ") to sign from " . ucfirst($document->user->first_name) . " " . ucfirst($document->user->last_name),
                                        'body' => ucfirst($document->user->first_name) . " " . ucfirst($document->user->last_name) . " has sent you a document.",
                                        'title' => 'go NDA',
                                        'type' => 'document',
                                        'document' => $document->id,
                                        'subtitle' => ucfirst($document->user->first_name) . " " . ucfirst($document->user->last_name) . " has sent you a document.",
                                        'key' => '5',
                                        'vibrate' => 1,
                                        'sound' => 1,
                                        'largeIcon' => 'large_icon',
                                        'smallIcon' => 'small_icon'
                                    );
                                    push_notification_send_driver($document->contact->userByMobileNumber->device_token, $msg_rec);
                                    //for push notification
                                }
                            }

                            if ($document->user->push_notification == 1) {
                                $receiver_name = !empty($document->contact->userByMobileNumber) ? $document->contact->userByMobileNumber->first_name . " " . $document->contact->userByMobileNumber->last_name : $document->contact->contact_first_name . " " . $document->contact->contact_last_name;
                                //for push notification
                                $msg_send = array(
                                    'body' => "Your document(#" . $document->id . ") has been sent to " . ucfirst($receiver_name),
                                    'title' => 'go NDA',
                                    'type' => 'document',
                                    'document' => $document->id,
                                    'subtitle' => "Your document(#" . $document->id . ") has been sent to " . ucfirst($receiver_name),
                                    'key' => '5',
                                    'vibrate' => 1,
                                    'sound' => 1,
                                    'largeIcon' => 'large_icon',
                                    'smallIcon' => 'small_icon'
                                );
                                push_notification_send_driver($document->user->device_token, $msg_send);
                                //for push notification
                            }
                        } elseif ($document->type == 2) {
                            if ($document->user->push_notification == 1) {
                                //for push notification
                                $msg_send = array(
                                    'body' => "Your document has been saved to My Drafts",
                                    'title' => 'go NDA',
                                    'type' => 'document',
                                    'document' => $document->id,
                                    'subtitle' => "Your document has been saved to My Drafts",
                                    'key' => '5',
                                    'vibrate' => 1,
                                    'sound' => 1,
                                    'largeIcon' => 'large_icon',
                                    'smallIcon' => 'small_icon'
                                );
                                push_notification_send_driver($document->user->device_token, $msg_send);
                                //for push notification
                            }
                        }

                        $document['global_content'] = $global_setting->content;
                        $document_data[] = $document;
                    }
                }

                if ($document_data) {
                    foreach ($document_data as $contact_data) {
                        $contact = Contact::where('id', $contact_data->contact_id)->first();
                        if ($contact) {
                            $user = User::where('mobile_number', $contact->contact_mobile_number)->first();
                            if ($user) {
                                $user_data = $user->sign_image;
                            }
                        }
                    }
                }
                DB::commit();
                return response()->json([
                    'status' => 1,
                    //'push_notification' => $push_notification_receiver,
                    'message' => $document->type == 1 ? "Your document has been sent." : "Your document has been saved to My Drafts.",
                    'sign_image_path' => URL::to('/') . '/public/storage/uploads/user-sign/',
                    'data' => $document_data,
                    'sign_image' => $user_data,

                ]);
            }
        } catch (\Exception $exception) {
            return error_response($exception);
        }
    }

    public function docList(Request $request)
    {
        try {

            $user = auth('api')->user();

            if ($user) {

                $current_plan = HomeSection6::userTransactions();
                if (!empty($current_plan)) {
                    $current_plan['is_premium'] = $user->is_premium;
                }

                $contact = Contact::where('contact_mobile_number', $user->mobile_number)->pluck('id');

                $documents = Document::with('contact.userByMobileNumber', 'stateUs:id,name', 'user:id,first_name,last_name,profile_image,sign_image,is_premium', 'templateData')
                    ->where('type', 1)
                    ->where(function ($query) use ($contact) {
                        $query->whereIn('contact_id', $contact)
                            ->orWhere('user_id', auth('api')->id());
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

                if ($documents->isNotEmpty()) {
                    foreach ($documents as $key => $document) {
                        //1 => receiver , 0 => sender
                        $document->is_received = $document->user_id == auth('api')->id() ? 0 : 1;
                        $document->can_send_reminder = $document->reminder_sent_at <= Carbon::now()->subHours(24) ? 1 : 0;
                        $document->sender_details = json_decode($document->sender_details);
                        $document->receiver_details = json_decode($document->receiver_details);

                        //for keyword
                        $keyword = [];
                        if ($document->keyword_id) {
                            $keyword_details = json_decode($document->keyword_id);
                            if ($keyword_details) {
                                foreach ($keyword_details as $k => $keyword_details_data) {
                                    $keyword[$k] = Keyword::where('id', $keyword_details_data->id)->first();
                                    $keyword[$k]['answer'] = $keyword_details_data->answer;
                                }
                            }
                        } else {
                            $keyword = [];
                        }
                        $documents[$key]['keywords'] = $keyword;
                        //for keyword
                    }


                    return response()->json([
                        "status" => 1,
                        "message" => 'Contact List',
                        'contact_image_path' => URL::to('/') . '/public/storage/uploads/contact-image/',
                        'profile_image_path' => URL::to('/') . '/public/storage/uploads/user-profile/',
                        'sign_image_path' => URL::to('/') . '/public/storage/uploads/user-sign/',
                        'template_path' => URL::to('/') . '/public/storage/uploads/pdf/',
                        "data" => $documents,
                        'current_plan' => $current_plan,
                    ]);
                }

                return response()->json([
                    "status" => 1,
                    "message" => 'No Document',
                    'contact_image_path' => URL::to('/') . '/public/storage/uploads/contact-image/',
                    'profile_image_path' => URL::to('/') . '/public/storage/uploads/user-profile/',
                    'sign_image_path' => URL::to('/') . '/public/storage/uploads/user-sign/',
                    'template_path' => URL::to('/') . '/public/storage/uploads/pdf/',
                    "data" => [],
                    'current_plan' => $current_plan,
                ]);
            }

            return response()->json([
                "status" => 0,
                "message" => 'Something went wrong',
                "data" => []
            ]);
        } catch (\Exception $exception) {
            return error_response($exception);
        }
    }

    public function receivedDocumentList()
    {
        try {

            $document = Document::with('contact', 'stateUs:id,name')
                ->where('contact_id', auth('api')->id())
                ->paginate(10);
            if ($document->isNotEmpty()) {
                return response()->json([
                    "status" => 1,
                    "message" => 'Contact List',
                    'contact_image_path' => URL::to('/') . '/public/storage/uploads/contact-image/',
                    "data" => $document
                ]);
            }
            return response()->json([
                "status" => 1,
                "message" => 'No Records',
                "data" => []
            ]);
        } catch (\Exception $exception) {
            return error_response($exception);
        }
    }

    public function statusChange(Request $request)
    {
        Log::info('START CALLING STATUSCHANGE API', $request->all());
        $validator = Validator::make($request->all(), [
            'document_id' => 'required|exists:documents,id',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }
        try {
            $document_id = (int)$request['document_id'];

            $document = Document::with('stateUs:id,name', 'user', 'contact.userByMobileNumber', 'templateData')
                ->where('id', $document_id)->first();

            $receiver_name = !empty($document->contact->userByMobileNumber) ? $document->contact->userByMobileNumber->first_name . " " . $document->contact->userByMobileNumber->last_name : $document->contact->contact_first_name . " " . $document->contact->contact_last_name;

            if ($document->auto_sign == 1 && $document->status == 2) {
                $document->status = 3;
                $document->save();

                if ($document->user) {


                    if ($document->user->push_notification == 1) {
                        $msg_sender = array(
                            'body' => "Your document(#" . $document->id . ") with " . ucfirst($receiver_name) . " has been completed.",
                            'title' => 'go NDA',
                            'type' => 'document',
                            'document' => $document->id,
                            'subtitle' => "Your document(#" . $document->id . ") with " . ucfirst($receiver_name) . " has been completed.",
                            'key' => '5',
                            'vibrate' => 1,
                            'sound' => 1,
                            'largeIcon' => 'large_icon',
                            'smallIcon' => 'small_icon'
                        );
                        push_notification_send_driver($document->user->device_token, $msg_sender);
                    }


                    if ($document->user->email_notification == 1) {

                        $url = Route('share', $document->id);
                        twilio_sms($document->user->country_code, $document->user->mobile_number, null, "Hi, its goNDA, Your document(#" . $document->id . ") with " . ucfirst($receiver_name) . " has been completed. Click to view. / " . $url);
                    }
                }


                if ($document->contact->userByMobileNumber) {
                    if ($document->contact->userByMobileNumber->push_notification == 1) {
                        $msg_receiver = array(
                            //'body' => "Document(#" . $document->id . ") has been completed",
                            'body' => "Your document(#" . $document->id . ") with " . ucfirst($document->user->first_name) . " " . ucfirst($document->user->last_name) . " has been completed.",
                            'title' => 'go NDA',
                            'type' => 'document',
                            'document' => $document->id,
                            'subtitle' => "Your document(#" . $document->id . ") with " . ucfirst($document->user->first_name) . " " . ucfirst($document->user->last_name) . " has been completed.",
                            'key' => '5',
                            'vibrate' => 1,
                            'sound' => 1,
                            'largeIcon' => 'large_icon',
                            'smallIcon' => 'small_icon'
                        );
                        push_notification_send_driver($document->contact->userByMobileNumber->device_token, $msg_receiver);
                    }

                    if ($document->contact->userByMobileNumber->email_notification == 1) {

                        $url = Route('share', $document->id);
                        twilio_sms($document->contact->userByMobileNumber->country_code, $document->contact->userByMobileNumber->mobile_number, null, "Hi, its goNDA, Your document(#" . $document->id . ") with " . ucfirst($document->user->first_name) . " " . ucfirst($document->user->last_name) . " has been completed. Click to view. / " . $url);
                    }
                }
            }

            // when sender not doing auto sign
            elseif ($document->auto_sign == 0 && $document->status == 2) {

                if ($request->has('sender_sign')) {
                    $filename = base64_to_image_sign($request['sender_sign']);
                    $document->sender_sign = $filename;
                } else {
                    $document->sender_sign = $document->user->sign_image;
                }

                if ($document->user) {

                    $notification_to_sender = new Notification();
                    $notification_to_sender->comment = "You have signed the document(#" . $document->id . ")";
                    $notification_to_sender->type = 'document';
                    $notification_to_sender->document_id = $document->id;
                    $notification_to_sender->user_id = $document->user_id;
                    $notification_to_sender->save();

                    if ($document->user->email_notification == 1) {
                        $url = Route('share', $document->id);
                        twilio_sms($document->user->country_code, $document->user->mobile_number, null, "Hi, its goNDA, You have signed the document(#" . $document->id . "). Click to view. / " . $url);
                    }

                    //for push notification
                    if ($document->user->push_notification == 1) {
                        $msg_sender = array(
                            'body' => "You have signed the document(#" . $document->id . ").",
                            'title' => 'go NDA',
                            'type' => 'document',
                            'document' => $document->id,
                            'subtitle' => "You have signed the document(#" . $document->id . ").",
                            'key' => '5',
                            'vibrate' => 1,
                            'sound' => 1,
                            'largeIcon' => 'large_icon',
                            'smallIcon' => 'small_icon'
                        );
                        push_notification_send_driver($document->user->device_token, $msg_sender);
                    }
                    //for push notification
                }

                if ($document->contact->userByMobileNumber) {

                    // $notification_to_receiver = new Notification();
                    // $notification_to_receiver->comment = ucfirst($document->user->first_name) . " has signed your document(#" . $document->id . ").";
                    // $notification_to_receiver->type = 'document';
                    // $notification_to_receiver->document_id = $document->id;
                    // $notification_to_receiver->user_id = $document->contact->userByMobileNumber->id;
                    // $notification_to_receiver->save();

                    if ($document->contact->userByMobileNumber->push_notification == 1) {
                        $msg_receiver = array(
                            'body' => ucfirst($document->user->first_name) . " has signed your document(#" . $document->id . ").",
                            'title' => 'go NDA',
                            'type' => 'document',
                            'document' => $document->id,
                            'subtitle' => ucfirst($document->user->first_name) . " has signed your document(#" . $document->id . ").",
                            'key' => '5',
                            'vibrate' => 1,
                            'sound' => 1,
                            'largeIcon' => 'large_icon',
                            'smallIcon' => 'small_icon'
                        );
                        push_notification_send_driver($document->contact->userByMobileNumber->device_token, $msg_receiver);
                    }

                    if ($document->contact->userByMobileNumber->email_notification == 1) {

                        $url = Route('share', $document->id);
                        twilio_sms($document->contact->userByMobileNumber->country_code, $document->contact->userByMobileNumber->mobile_number, null, "Hi, it's goNDA," . ucfirst($document->user->first_name) . "has signed your document(#" . $document->id . "). Click to view. / " . $url);
                    }
                }

                $document->status = 3;
                $document->save();

                if ($document->contact->userByMobileNumber) {

                    $notification_to_receiver = new Notification();
                    $notification_to_receiver->comment = "Your document(#" . $document->id . ") with " . ucfirst($document->user->first_name) . " " . ucfirst($document->user->last_name) . " has been completed.";
                    $notification_to_receiver->type = 'document';
                    $notification_to_receiver->document_id = $document->id;
                    $notification_to_receiver->user_id = $document->contact->userByMobileNumber->id;
                    $notification_to_receiver->save();

                    if ($document->contact->userByMobileNumber->email_notification == 1) {

                        $url = Route('share', $document->id);
                        twilio_sms($document->contact->userByMobileNumber->country_code, $document->contact->userByMobileNumber->mobile_number, null, "Hi, it's goNDA, Your document(#" . $document->id . ") with " . ucfirst($document->user->first_name) . " " . ucfirst($document->user->last_name) . " has been completed. Click to view. / " . $url);
                    }

                    if ($document->contact->userByMobileNumber->push_notification == 1) {
                        $msg_receiver2 = array(
                            'body' => "Your document(#" . $document->id . ") with " . ucfirst($document->user->first_name) . " " . ucfirst($document->user->last_name) . " has been completed.",
                            'title' => 'go NDA',
                            'type' => 'document',
                            'document' => $document->id,
                            'subtitle' => "Your document(#" . $document->id . ") with " . ucfirst($document->user->first_name) . " " . ucfirst($document->user->last_name) . " has been completed.",
                            'key' => '5',
                            'vibrate' => 1,
                            'sound' => 1,
                            'largeIcon' => 'large_icon',
                            'smallIcon' => 'small_icon'
                        );
                        push_notification_send_driver($document->contact->userByMobileNumber->device_token, $msg_receiver2);
                    }
                }

                if ($document->user) {

                    // $notification_to_receiver = new Notification();
                    // $notification_to_receiver->comment = "Your document(#".$document->id.") with ".ucfirst($receiver_name)." has been completed.";
                    // $notification_to_receiver->type = 'document';
                    // $notification_to_receiver->document_id = $document->id;
                    // $notification_to_receiver->user_id = $document->user->id;
                    // $notification_to_receiver->save();

                    if ($document->user->push_notification == 1) {
                        $msg_sender1 = array(
                            'body' => "Your document(#" . $document->id . ") with " . ucfirst($receiver_name) . " has been completed.",
                            'title' => 'go NDA',
                            'type' => 'document',
                            'document' => $document->id,
                            'subtitle' => "Your document(#" . $document->id . ") with " . ucfirst($receiver_name) . " has been completed.",
                            'key' => '5',
                            'vibrate' => 1,
                            'sound' => 1,
                            'largeIcon' => 'large_icon',
                            'smallIcon' => 'small_icon'
                        );
                        push_notification_send_driver($document->user->device_token, $msg_sender1);
                    }

                    if ($document->user->email_notification == 1) {

                        $url = Route('share', $document->id);
                        twilio_sms($document->user->country_code, $document->user->mobile_number, null, "Hi, it's goNDA, Your document(#" . $document->id . ") with " . ucfirst($receiver_name) . " has been completed. Click to view. / " . $url);
                    }
                }
            }
            // when receiver send back
            elseif ($document->status == 1) {

                if ($request->has('receiver_sign')) {
                    $filename = base64_to_image_sign($request['receiver_sign']);
                    $document->receiver_sign = $filename;
                } else {
                    $document->receiver_sign = $document->contact->userByMobileNumber->sign_image ?? '';
                }

                $document->status = $document->auto_sign == 1 ? 3 : 2;
                $document->save();


                if ($document->status == 2) {

                    $notification_to_sender = new Notification();
                    $notification_to_sender->comment = "You have signed the document(#" . $document->id . ")";
                    $notification_to_sender->type = 'document';
                    $notification_to_sender->document_id = $document->id;
                    $notification_to_sender->user_id = $document->user_id;
                    $notification_to_sender->save();

                    if ($document->contact->userByMobileNumber->email_notification == 1) {

                        $url = Route('share', $document->id);
                        twilio_sms($document->contact->userByMobileNumber->country_code, $document->contact->userByMobileNumber->mobile_number, null, "Hi, it's goNDA, You have signed the document(#" . $document->id . "). Click to view. / " . $url);
                    }
                    //for push notification
                    if ($document->contact->userByMobileNumber->push_notification == 1) {
                        $msg_sender = array(
                            'body' => "You have signed the document(#" . $document->id . ")",
                            'title' => 'go NDA',
                            'type' => 'document',
                            'document' => $document->id,
                            'subtitle' => "You have signed the document(#" . $document->id . ")",
                            'key' => '5',
                            'vibrate' => 1,
                            'sound' => 1,
                            'largeIcon' => 'large_icon',
                            'smallIcon' => 'small_icon'
                        );
                        push_notification_send_driver($document->contact->userByMobileNumber->device_token, $msg_sender);
                    }
                    //for push notification

                    //for push notification
                    if ($document->user) {

                        if ($document->user->push_notification == 1) {
                            $msg_receiver = array(
                                'body' => ucfirst($document->contact->userByMobileNumber->first_name) . "has signed your document(#" . $document->id . ").",
                                'title' => 'go NDA',
                                'type' => 'document',
                                'document' => $document->id,
                                'subtitle' => ucfirst($document->contact->userByMobileNumber->first_name) . "has signed your document(#" . $document->id . ").",
                                'key' => '5',
                                'vibrate' => 1,
                                'sound' => 1,
                                'largeIcon' => 'large_icon',
                                'smallIcon' => 'small_icon'
                            );
                            push_notification_send_driver($document->user->device_token, $msg_receiver);
                        }

                        if ($document->user->email_notification == 1) {
                            $url = Route('share', $document->id);
                            twilio_sms($document->user->country_code, $document->user->mobile_number, null, "Hi, it's goNDA, " . ucfirst($document->contact->userByMobileNumber->first_name) . "has signed your document(#" . $document->id . "). Click to view. / " . $url);
                        }
                    }
                    //for push notification
                }

                if ($document->status == 3) {

                    if ($document->contact->userByMobileNumber) {


                        $notification_to_receiver = new Notification();
                        $notification_to_receiver->comment = "Your document(#" . $document->id . ") with " . ucfirst($document->user->first_name) . " " . ucfirst($document->user->last_name) . " has been completed.";
                        $notification_to_receiver->type = 'document';
                        $notification_to_receiver->document_id = $document->id;
                        $notification_to_receiver->user_id = $document->contact->userByMobileNumber->id;
                        $notification_to_receiver->save();

                        if ($document->contact->userByMobileNumber->email_notification == 1) {
                            $url = Route('share', $document->id);
                            twilio_sms($document->contact->userByMobileNumber->country_code, $document->contact->userByMobileNumber->mobile_number, null, "Hi, it's goNDA, Your document(#" . $document->id . ") with " . ucfirst($document->user->first_name) . " " . ucfirst($document->user->last_name) . " has been completed. Click to view. / " . $url);
                        }

                        if ($document->contact->userByMobileNumber->push_notification == 1) {
                            $msg_receiver = array(
                                'body' => "Your document(#" . $document->id . ") with " . ucfirst($document->user->first_name) . " " . ucfirst($document->user->last_name) . " has been completed.",
                                'title' => 'go NDA',
                                'type' => 'document',
                                'document' => $document->id,
                                'subtitle' => "Your document(#" . $document->id . ") with " . ucfirst($document->user->first_name) . " " . ucfirst($document->user->last_name) . " has been completed.",
                                'key' => '5',
                                'vibrate' => 1,
                                'sound' => 1,
                                'largeIcon' => 'large_icon',
                                'smallIcon' => 'small_icon'
                            );
                            push_notification_send_driver($document->contact->userByMobileNumber->device_token, $msg_receiver);
                        }
                    }

                    if ($document->user) {

                        // $notification_to_receiver = new Notification();
                        // $notification_to_receiver->comment = "Your document(#" . $document->id . ") with " . ucfirst($receiver_name) . " has been completed.";
                        // $notification_to_receiver->type = 'document';
                        // $notification_to_receiver->document_id = $document->id;
                        // $notification_to_receiver->user_id = $document->user->id;
                        // $notification_to_receiver->save();

                        if ($document->user->push_notification == 1) {
                            $msg_sender = array(
                                'body' => "Your document(#" . $document->id . ") with " . ucfirst($receiver_name) . " has been completed.",
                                'title' => 'go NDA',
                                'type' => 'document',
                                'document' => $document->id,
                                'subtitle' => "Your document(#" . $document->id . ") with " . ucfirst($receiver_name) . " has been completed.",
                                'key' => '5',
                                'vibrate' => 1,
                                'sound' => 1,
                                'largeIcon' => 'large_icon',
                                'smallIcon' => 'small_icon'
                            );
                            push_notification_send_driver($document->user->device_token, $msg_sender);
                        }

                        if ($document->user->email_notification == 1) {
                            $url = Route('share', $document->id);
                            twilio_sms($document->user->country_code, $document->user->mobile_number, null, "Hi, it's goNDA, Your document(#" . $document->id . ") with " . ucfirst($receiver_name) . " has been completed. Click to view. / " . $url);
                        }
                    }
                }

                // deduct credit point
                //$template = TemplateManagement::where(['slug'=>'nda_doc', 'status'=>1])->first();
                $template = TemplateManagement::where(['id' => $document->template, 'status' => 1])->first();
                if ($template) {
                    $user_transaction = UserTransaction::where('user_id', $document->user_id)->first();
                    if ($user_transaction) {
                        if ($user_transaction->plan_id != 3) {
                            $user_transaction->credit_points = $user_transaction->credit_points - $template->credit_points;
                            $user_transaction->save();
                        }
                        $transaction = new Transaction();
                        $transaction->document_id = $document->id;
                        $transaction->user_id = $document->user_id;
                        $transaction->contact_id = $document->contact_id;
                        $transaction->credit_points = $template->credit_points;
                        $transaction->save();
                    }
                }
            }

            //for nda template document
            $temp_path = 'public/storage/uploads/pdf/';
            if ($document) {
                $sender_detail = json_decode($document->sender_details);

                $receiver_detail = json_decode($document->receiver_details);



                //for keywords
                $keyword_detail = json_decode($document->keyword_id);

                $question_and_answer = [];
                $keywords_data = '';
                if ($keyword_detail) {
                    foreach ($keyword_detail as $key => $keyword_detail_data) {
                        $keywords = Keyword::findOrFail($keyword_detail_data->id);
                        $question_and_answer[] = [
                            'keywords' => $keywords->keyword,
                            'question' => $keywords->question,
                            'answer' => $keyword_detail_data->answer,
                        ];
                        $keywords_array_key[] = "{" . $keywords->keyword . "}";
                        $keywords_with_key_value[$keywords->keyword] = $keyword_detail_data->answer;
                    }
                }

                //for keywords
                //$sender_sign = $document->sender_sign ?? $document->user->sign_image;
                $sender_sign = $document->sender_sign;
                //$receiver_sign = $document->receiver_sign ?? $document->contact->userByMobileNumber->sign_image;
                $receiver_sign = $document->receiver_sign;

                $sender_company_details = CompanyDetail::where('user_id', $document->user->id)->first();
                $sender_mailling_address = MaillingAddress::where('user_id', $document->user->id)->first();

                $receiver_company_details = CompanyDetail::where('user_id', $document->contact->userByMobileNumber->id)->first();
                $receiver_mailling_address = MaillingAddress::where('user_id', $document->contact->userByMobileNumber->id)->first();

                $sender_image = !empty($sender_detail->profile_image) ? $sender_detail->profile_image : 'avtar.png';

                $receiver_image = !empty($receiver_detail->profile_image) ? $receiver_detail->profile_image : 'avtar.png';

                $user_data = [
                    'sender_first_name' => ucfirst($sender_detail->first_name),
                    'sender_last_name' => ucfirst($sender_detail->last_name),
                    'sender_email' => ucfirst($sender_detail->email),
                    'sender_mobile_number' => ucfirst($sender_detail->mobile_number),
                    'sender_profile_image' => '<img width="50" height="50" src="' . asset("public/storage/uploads/user-profile/" . $sender_image) . '"/>',
                    'receiver_first_name' => ucfirst($receiver_detail->first_name),
                    'receiver_last_name' => ucfirst($receiver_detail->last_name),
                    'receiver_email' => ucfirst($receiver_detail->email),
                    'receiver_mobile_number' => ucfirst($receiver_detail->mobile_number),
                    'receiver_profile_image' => '<img width="50" height="50" src="' . asset("public/storage/uploads/user-profile/" . $receiver_image) . '"/>',
                    'sender_company_name' => $sender_company_details->company_name ?? 'NA',
                    'sender_job_title' => $sender_company_details->job_title ?? 'NA',
                    'receiver_company_name' => $receiver_company_details->company_name ?? 'NA',
                    'receiver_job_title' => $receiver_company_details->job_title ?? 'NA',
                    'sender_street_address' => $sender_mailling_address->street_address ?? 'NA',
                    'sender_apartment_number' => $sender_mailling_address->apartment_number ?? 'NA',
                    'sender_country_name' => $sender_mailling_address->countries->name ?? 'NA',
                    'sender_state_name' => $sender_mailling_address->states->name ?? 'NA',
                    'sender_city_name' => $sender_mailling_address->city_name ?? 'NA',
                    'sender_zip_code' => $sender_mailling_address->zip_code ?? 'NA',
                    'receiver_street_address' => $receiver_mailling_address->street_address ?? 'NA',
                    'receiver_apartment_number' => $receiver_mailling_address->apartment_number ?? 'NA',
                    'receiver_country_name' => $receiver_mailling_address->countries->name ?? 'NA',
                    'receiver_state_name' => $receiver_mailling_address->states->name ?? 'NA',
                    'receiver_city_name' => $receiver_mailling_address->city_name ?? 'NA',
                    'receiver_zip_code' => $receiver_mailling_address->zip_code ?? 'NA',
                    'content' => $document->content ?? 'NA',
                    //'question_and_answer' => $keywords_data,
                    'sender_date' => $sender_detail->created_at,
                    'receiver_date' => $receiver_detail->created_at,
                    'sender_sign_image' => '<img width="50" height="50" src="' . asset("public/storage/uploads/user-sign/" . $sender_sign) . '"/>',
                    'receiver_sign_image' => '<img width="50" height="50" src="' . asset("public/storage/uploads/user-sign/" . $receiver_sign) . '"/>',
                ];

                //$email_template = TemplateManagement::where('slug', 'nda_doc')->first();
                $email_template = TemplateManagement::where(['id' => $document->template, 'status' => 1])->first();

                $message = str_replace(array(
                    '{sender_first_name}', '{sender_last_name}', '{sender_email}', '{sender_mobile_number}', '{sender_profile_image}', '{receiver_first_name}', '{receiver_last_name}', '{receiver_email}',
                    '{receiver_mobile_number}', '{receiver_profile_image}',
                    '{sender_company_name}', '{sender_job_title}', '{receiver_company_name}', '{receiver_job_title}',
                    '{sender_street_address}', '{sender_apartment_number}', '{sender_country_name}', '{sender_state_name}',
                    '{sender_city_name}', '{sender_zip_code}', '{receiver_street_address}', '{receiver_apartment_number}',
                    '{receiver_country_name}', '{receiver_state_name}', '{receiver_city_name}', '{receiver_zip_code}',
                    '{content}', '{sender_date}', '{receiver_date}', '{sender_sign_image}', '{receiver_sign_image}',
                ), $user_data, $email_template->content);
                if (count($keywords_array_key ?? [])) {
                    $message = str_replace($keywords_array_key, $keywords_with_key_value, $message);
                }

                $mpdf = new Mpdf(['tempDir' => $temp_path]);
                $mpdf->WriteHTML($message);
                $pdf_name = 'NDA-' . $document->id . '.pdf';
                $mpdf->Output($temp_path . $pdf_name, 'F');
                $content_pdf = asset($temp_path . $pdf_name);
                $document->pdf = $pdf_name;
                $document->save();
            }
            $document['template_path'] = $content_pdf;
            $document['is_received'] = $document->user_id == auth('api')->id() ? 0 : 1;
            $document->sender_details = json_decode($document->sender_details);

            $document->receiver_details = json_decode($document->receiver_details);
            $document->keywords = $question_and_answer;
            $message = [];
            if ($document->status == 3) {
                $message = "Document(#" . $document->id . ") has been completed.";
            } elseif ($document->status == 2) {
                $message = "Success! You have signed this document(#" . $document->id . ") and it has been sent back to the Sender.";
            } else {
                $message = "Your sign has been placed.";
            }

            return response()->json([
                "status" => 1,
                //"message" => $document->status==3 ? "Document(#".$document->id.") has been completed." : "Your sign has been placed.",
                "message" => $message,
                'contact_image_path' => URL::to('/') . '/public/storage/uploads/contact-image/',
                'profile_image_path' => URL::to('/') . '/public/storage/uploads/user-profile/',
                'sign_image_path' => URL::to('/') . '/public/storage/uploads/user-sign/',
                "template_path" => $content_pdf,
                "data" => $document,
            ]);
        } catch (\Exception $exception) {
            return error_response($exception);
        }
    }

    public function notarizeChange(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'document_id' => 'required|exists:documents,id',
            'token_id' => 'required'
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }
        try {
            $document = Document::with('stateUs:id,name', 'user', 'contact.userByMobileNumber')
                ->where('id', $request['document_id'])->first();
            $user = auth('api')->user();

            $token_id = $request['token_id'];
            if ($document->notarize == 0) {
                $user = auth('api')->user();

                $global = NotaryDetail::first();

                //for user charge by stripe
                $stripe = new StripeClient(
                    env('STRIPE_SECRET')
                );

                $charge = $stripe->charges->create([
                    'amount' => $global->amount * 100,
                    'currency' => 'usd',
                    //'customer' => $user->stripe_customer_id,
                    'source' => $token_id,
                    'description' => 'Gonda test',
                ]);
                if ($charge->status == 'succeeded') {
                    $document->notarize = 1;
                    $document->save();
                    if ($document->user->push_notification == 1) {
                        //for push notification
                        $msg_send = array(
                            'body' => 'Your notary request has been sent to support@getgonda.com.',
                            'title' => 'go NDA',
                            'type' => 'document',
                            'document' => $document->id,
                            'subtitle' => 'Your notary request has been sent to support@getgonda.com.',
                            'key' => '5',
                            'vibrate' => 1,
                            'sound' => 1,
                            'largeIcon' => 'large_icon',
                            'smallIcon' => 'small_icon'
                        );
                        push_notification_send_driver($document->user->device_token, $msg_send);
                        //for push notification
                    }
                    $mail_array = [
                        'first_name' => ucfirst($user['first_name']),
                        'last_name' => ucfirst($user['last_name']),
                        'document_id' => $request['document_id'],
                    ];

                    $email_template = EmailTemplateManagement::where('slug', 'notary_email')->first();
                    $message = str_replace(array('{first_name}', '{last_name}', '{document_id}'), $mail_array, $email_template->content);

                    $to_mail = "support@getgonda.com";
                    $subject = $email_template->subject;
                    $from_mail = env('MAIL_FROM_ADDRESS');

                    $data['msg'] = $message;

                    Mail::send('admin.emails.notary-change', $data, function ($message) use ($to_mail, $subject, $from_mail) {
                        $message->to($to_mail);
                        $message->subject($subject);
                        $message->from($from_mail);
                    });
                    $message = 'Your notary request has been sent to support@getgonda.com.';
                    $status = 1;
                    $document->sender_details = json_decode($document->sender_details);
                    $document->receiver_details = json_decode($document->receiver_details);

                    //for keyword
                    $keyword = [];
                    if ($document->keyword_id) {
                        $keyword_details = json_decode($document->keyword_id);
                        if ($keyword_details) {
                            foreach ($keyword_details as $k => $keyword_details_data) {
                                $keyword[$k] = Keyword::where('id', $keyword_details_data->id)->first();
                                $keyword[$k]['answer'] = $keyword_details_data->answer;
                            }
                        }
                    } else {
                        $keyword = [];
                    }
                    $document['keywords'] = $keyword;
                    //for keyword
                } else {
                    $message = $charge->failure_message;
                    $status = 0;
                    $document = [];
                }
                return response()->json([
                    "status" => $status,
                    "message" => $message,
                    'contact_image_path' => URL::to('/') . '/public/storage/uploads/contact-image/',
                    'profile_image_path' => URL::to('/') . '/public/storage/uploads/user-profile/',
                    'sign_image_path' => URL::to('/') . '/public/storage/uploads/user-sign/',
                    "data" => $document
                ]);
            }

            return response()->json([
                "status" => 0,
                "message" => 'Your notary request has not been sent to support@getgonda.com.',
                "data" => []
            ]);
        } catch (\Exception $exception) {
            return error_response($exception);
        }
    }

    public function draftList(Request $request)
    {
        $drafts = Document::with('contact')->where([
            'user_id' => auth('api')->id(),
            'type' => 2,
        ])->get();
        //        //for keyword
        //        if ($drafts->isNotEmpty()){
        //            foreach ($drafts as $drafts_data){
        //                if ($drafts_data->keyword_id){
        //                    $keyword_details = json_decode($drafts_data->keyword_id);
        //                    if ($keyword_details){
        //                        $Keyword = [];
        //                        foreach ($keyword_details as $keyword_details_data){
        //                           $Keyword = Keyword::where('id', $keyword_details_data->id)->first();
        //                            $Keyword['answer'] = $keyword_details_data->answer;
        //                        }
        //                    }
        //                }
        //            }
        //        }
        //        //for keyword
        $document_by_batch = $drafts->groupBy('batch');
        $response = [];
        $contact_data = [];
        if ($document_by_batch->isNotEmpty()) {
            foreach ($document_by_batch as $batch => $draft_data) {
                $batch_contact_data = [];
                $Keyword = [];
                foreach ($draft_data as $data) {
                    //for keyword
                    if ($data->keyword_id) {
                        $keyword_details = json_decode($data->keyword_id);
                        if ($keyword_details) {
                            foreach ($keyword_details as $key => $keyword_details_data) {
                                $Keyword[$key] = Keyword::where('id', $keyword_details_data->id)->first();
                                $Keyword[$key]['answer'] = $keyword_details_data->answer;
                            }
                        }
                    }
                    //for keyword
                    $batch_contact_data[] = [
                        'document_id' => $data->id,
                        'contact_id' => $data->contact->id ?? null,
                        'contact_first_name' => $data->contact->contact_first_name ?? null,
                        'contact_last_name' => $data->contact->contact_last_name ?? null,
                        'contact_mobile_number' => $data->contact->contact_mobile_number ?? null,
                        'contact_country_code' => $data->contact->contact_country_code ?? null,
                        'contact_image' => $data->contact->contact_image ?? null,
                    ];
                }
                //$data = $data->toArray();
                $response[] = [
                    'keywords' => $Keyword,
                    'contacts' => $batch_contact_data,
                    'ndaContent' => $draft_data[0]['content'],
                    'autoSign' => $draft_data[0]['auto_sign'],
                    'template' => $draft_data[0]['template'],
                    'state_id' => $draft_data[0]['state_id'],
                ];
            }
            return response()->json([
                "status" => 1,
                "message" => 'Draft list data',
                "contact_image_path" => URL::to('/') . '/public/storage/uploads/contact-image/',
                "data" => $response
            ]);
        }
        return response()->json([
            "status" => 1,
            "message" => 'No Records',
            "data" => []
        ]);
    }

    public function templateList(Request $request)
    {
        $template = TemplateManagement::where('status', 1)->get();
        $response = [];

        $temp_path = 'public/storage/uploads/pdf/';
        if ($template->isNotEmpty()) {
            foreach ($template as $key => $item) {
                $keyword = [];
                if ($item->keyword_id) {
                    $ex_k = explode(',', $item->keyword_id);
                    $keyword = Keyword::whereIn('id', $ex_k)->orderBy('position_order')->get();
                }
                $response[] = [
                    'id' => $item->id,
                    'credits' => $item->credit_points,
                    'nda_summary' => $item->nda_summary,
                    'status' => $item->status,
                    'pdf_name' => $item->pdf_name,
                    'keyword' => $keyword,
                ];
                // $mpdf = new Mpdf(['tempDir' => $temp_path]);
                // $mpdf->WriteHTML($item->content);
                // $pdf_name = $item->title;
                // //$pdf_name = 'NDA-'.$item->id.'.pdf';
                // $content_pdf = $mpdf->Output($temp_path . $pdf_name, 'F');
                $response[$key]['content_pdf'] = asset($temp_path . $item->pdf_name);
            }
        }
        return response()->json([
            'status' => 1,
            'message' => 'Template List',
            //"template_path" => URL::to('/') . '/public/storage/uploads/pdf/',
            'data' => $response
        ]);
    }

    public function sendNdaOnEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_id' => 'required|exists:documents,id',
            'email' => 'required|email|max:255',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }
        try {
            $document = Document::where('id', $request['document_id'])->first();
            //$email_address = $request['email'];
            $pdf_file = URL::to('/') . '/public/storage/uploads/pdf/' . $document->pdf;
            //            $subject = "Send a Copy Request";
            //            $data= [
            //                'user_name' => "Name"
            //            ];
            if ($document) {
                $email_template = EmailTemplateManagement::where('slug', 'send_nda')->first();
                $message = str_replace([], [], $email_template->content);
                $subject = $email_template['subject'];
                $to_email = $request['email'];
                $data['msg'] = $message;
                Mail::send('admin.emails.template', $data, function ($message) use ($to_email, $subject, $pdf_file) {
                    $message->to($to_email)
                        ->subject($subject)
                        ->from(env('MAIL_FROM_ADDRESS'))
                        ->attach($pdf_file);
                });
                //                Mail::send('admin.emails.template',$data, function ($message) use ($email_address, $subject, $pdf_file) {
                //                    $message->to($email_address)
                //                        ->subject($subject)
                //                        ->from(env('MAIL_FROM_ADDRESS'))
                //                        ->attach($pdf_file);
                //                });
                return response()->json([
                    'status' => 1,
                    'message' => 'Email has been sent successfully.',
                ]);
            }
            return response()->json([
                'status' => 1,
                'message' => 'Something went wrong.',
            ]);
        } catch (\Exception $exception) {
            return error_response($exception);
        }
    }

    public function reminderSentAt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_id' => 'required|exists:documents,id',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }
        try {
            $document = Document::with('stateUs:id,name', 'user', 'contact.userByMobileNumber')->where(['id' => $request['document_id'], 'user_id' => auth('api')->id()])->first();
            //dd($document->contact->userByMobileNumber->id);
            if (!empty($document)) {
                $document->reminder_sent_at = Carbon::now();
                $document->save();

                $sender_detail = json_decode($document->sender_details);

                $receiver_detail = json_decode($document->receiver_details);


                if (!empty($receiver_detail)) {
                    $user_details = [
                        'first_name' => ucfirst($receiver_detail->first_name) ?? '',
                        'last_name' => ucfirst($receiver_detail->last_name) ?? '',
                        'email' => $receiver_detail->email,
                        'country_code' => $receiver_detail->country_code ?? '',
                        'mobile_number' => $receiver_detail->mobile_number ?? '',
                    ];

                    //send_email($user_details, 'reminder_mail', 'reminder');

                }

                if ($document->contact->userByMobileNumber) {

                    $notification = new Notification();
                    $notification->comment = "You have received reminder for document(#" . $document->id . ") sign.";
                    $notification->type = 'document';
                    $notification->document_id = $document->id;
                    $notification->user_id = $document->contact->userByMobileNumber->id ?? null;
                    $notification->save();


                    if ($document->contact->userByMobileNumber->email_notification == 1) {
                        $url = Route('share', $document->id);
                        twilio_sms($document->contact->userByMobileNumber->country_code, $document->contact->userByMobileNumber->mobile_number, null, "Hi, it's goNDA, You have received reminder to sign document(#" . $document->id . "). Click to view. / " . $url);
                    }

                    if ($document->contact->userByMobileNumber->push_notification == 1) {
                        $msg_send = array(
                            'body' => "You have received reminder for document(#" . $document->id . ") sign.",
                            'title' => 'go NDA',
                            'type' => 'document',
                            'document' => $document->id,
                            'subtitle' => "You have received reminder for document(#" . $document->id . ") sign.",
                            'key' => '5',
                            'vibrate' => 1,
                            'sound' => 1,
                            'largeIcon' => 'large_icon',
                            'smallIcon' => 'small_icon'
                        );
                        push_notification_send_driver($document->contact->userByMobileNumber->device_token, $msg_send);
                    }
                }

                $document->sender_details = $sender_detail;
                $document->receiver_details = $receiver_detail;

                //for keyword
                $keyword = [];
                if ($document->keyword_id) {
                    $keyword_details = json_decode($document->keyword_id);
                    if ($keyword_details) {
                        foreach ($keyword_details as $k => $keyword_details_data) {
                            $keyword[$k] = Keyword::where('id', $keyword_details_data->id)->first();
                            $keyword[$k]['answer'] = $keyword_details_data->answer;
                        }
                    }
                } else {
                    $keyword = [];
                }
                $document['keywords'] = $keyword;
                //for keyword

                return response()->json([
                    'status' => 1,
                    'message' => 'Reminder has been sent.',
                    'contact_image_path' => URL::to('/') . '/public/storage/uploads/contact-image/',
                    'profile_image_path' => URL::to('/') . '/public/storage/uploads/user-profile/',
                    'sign_image_path' => URL::to('/') . '/public/storage/uploads/user-sign/',
                    'data' => $document
                ]);
            }
            return response()->json([
                'status' => 0,
                'message' => 'Something went wrong',
                'data' => []
            ]);
        } catch (\Exception $exception) {
            return error_response($exception);
        }
    }

    public function subscriptionList(Request $request)
    {
        try {
            $plan = HomeSection6::where('status', 1)->get();
            $transaction = Transaction::with('contact.userByMobileNumber')
                ->where('user_id', auth('api')->id())
                ->orderBy('created_at', 'desc')
                ->get();
            $receiver_data = [];
            if ($transaction->isNotEmpty()) {
                foreach ($transaction as $transaction_data) {
                    $receiver_data[] = [
                        'document_id' => $transaction_data->document_id,
                        'first_name' => $transaction_data->contact->userByMobileNumber->first_name,
                        'last_name' => $transaction_data->contact->userByMobileNumber->last_name,
                        'profile_image' => $transaction_data->contact->userByMobileNumber->profile_image,
                        'credit_points' => $transaction_data->credit_points,
                        'created_at' => $transaction_data->created_at,
                    ];
                }
            }
            if ($plan->isNotEmpty()) {
                return response()->json([
                    'status' => 1,
                    'message' => 'Subscription List',
                    'profile_image_path' => URL::to('/') . '/public/storage/uploads/user-profile/',
                    'data' => $plan,
                    'card_list' => StripePaymentController::cardList(),
                    'current_plan' => HomeSection6::userTransactions(),
                    'transaction' => $receiver_data,
                    'can_cancelled_subscription' => HomeSection6::canCancelledSubscription(),
                ]);
            }
            return response()->json([
                'status' => 1,
                'message' => 'No Records',
                'data' => []
            ]);
        } catch (\Exception $exception) {
            return error_response($exception);
        }
    }

    public function documentDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_id' => 'required|exists:documents,id',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }
        try {
            $document = Document::with('contact.userByMobileNumber', 'stateUs:id,name', 'user:id,first_name,last_name,profile_image,sign_image')
                ->where('id', $request['document_id'])->first();
            if (!empty($document)) {
                $document->is_received = $document->user_id == auth('api')->id() ? 0 : 1;
                $document->sender_details = json_decode($document->sender_details);
                $document->receiver_details = json_decode($document->receiver_details);
                //for keyword
                $keyword = [];
                if ($document->keyword_id) {
                    $keyword_details = json_decode($document->keyword_id);
                    if ($keyword_details) {
                        foreach ($keyword_details as $k => $keyword_details_data) {
                            $keyword[$k] = Keyword::where('id', $keyword_details_data->id)->first();
                            $keyword[$k]['answer'] = $keyword_details_data->answer;
                        }
                    }
                } else {
                    $keyword = [];
                }
                $document['keywords'] = $keyword;
                //for keyword

                return response()->json([
                    'status' => 1,
                    'message' => 'Document Details',
                    'contact_image_path' => URL::to('/') . '/public/storage/uploads/contact-image/',
                    'profile_image_path' => URL::to('/') . '/public/storage/uploads/user-profile/',
                    'sign_image_path' => URL::to('/') . '/public/storage/uploads/user-sign/',
                    'template_path' => URL::to('/') . '/public/storage/uploads/pdf/',
                    'data' => $document
                ]);
            }
            return response()->json([
                'status' => 1,
                'message' => 'No Records.',
                'data' => []
            ]);
        } catch (\Exception $exception) {
            return error_response($exception);
        }
    }

    public function Docupdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contacts' => 'required|array',
            'contacts.*.contact_id' => 'required|exists:contacts,id',
            'contacts.*.document_id' => 'sometimes|nullable|exists:documents,id',
            'content' => 'sometimes|nullable|string',
            //'state_id' => 'required_if:type,=,1',
            'template' => 'required_if:type,=,1',
            'keywords' => 'required_if:type,=,1|array',
            'keywords.*.id' => 'required_if:type,=,1|exists:keywords,id',
            'keywords.*.answer' => 'required_if:type,=,1',
            'auto_sign' => 'required|in:0,1',
            'type' => 'required|in:1,2',
        ], [
            'contact_id.*.exists' => 'The entered contact id does not exists.',
            'contact_id.*.unique' => 'The contact id has already been taken.',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }
        $document_data = [];
        $user_data = [];

        foreach ($request['to_delete_docs'] as $deletecontact) {
            Document::where('id', $deletecontact['document_id'])->where('contact_id', $deletecontact['contact_id'])->delete();
        }

        $batch_id = Document::where('id', $request['document_id'])->first();

        try {
            DB::beginTransaction();
            $user = auth('api')->user();
            $global_setting = GlobalSettingManagement::first();
            if (!empty($request['contacts'])) {
                $last_draft = Document::where('type', 2)->latest()->first('batch');
                $batch = !empty($last_draft) ? $last_draft->batch + 1 : 1;
                foreach ($request['contacts'] as $contact) {
                    $updatedoc = Document::where('id', $contact['document_id'])->where('contact_id', $contact['contact_id'])->first();
                    if (!empty($updatedoc)) {
                        $document = Document::find($contact['document_id']);
                        $document->content    = $request['content'];
                        $document->state_id   = $request['state_id'];
                        $document->template   = $request['template'];
                        $document->auto_sign  = $request['auto_sign'];
                        $document->sender_sign = $request['auto_sign'] == 1 ? $user->sign_image : null;
                        $document->keyword_id = json_encode($request['keywords']);
                        $document->save();
                        $document_data[] = $document;
                    } else {
                        $document = new Document();
                        $document->user_id    = auth('api')->id();
                        $document->contact_id = $contact['contact_id'];
                        $document->content    = $request['content'];
                        $document->state_id   = $request['state_id'];
                        $document->template   = $request['template'];
                        $document->auto_sign  = $request['auto_sign'];
                        $document->sender_sign = $request['auto_sign'] == 1 ? $user->sign_image : null;
                        $document->type       = $request['type'];
                        $document->status     = $request['type'] == 1 ? 1 : 0;
                        $document->keyword_id = json_encode($request['keywords']);
                        $document->batch      = $batch_id->batch;
                        $document->save();

                        // update sender and receiver details
                        $document->sender_details = json_encode($document->user);
                        $document->receiver_details = json_encode($document->contact->userByMobileNumber);
                        $document->save();

                        $notification_to_sender = new Notification();
                        $notification_to_sender->comment = "You have sent document(#" . $document->id . ") to " . ucfirst($document->contact->contact_first_name) . " " . ucfirst($document->contact->contact_last_name);
                        $notification_to_sender->type = 'document';
                        $notification_to_sender->document_id = $document->id;
                        $notification_to_sender->user_id = $document->user_id;
                        $notification_to_sender->save();

                        $notification_to_receiver = new Notification();
                        $notification_to_receiver->comment = "You have received document(#" . $document->id . ") to sign from " . ucfirst($document->user->first_name) . " " . ucfirst($document->user->last_name);
                        $notification_to_receiver->type = 'document';
                        $notification_to_receiver->document_id = $document->id;
                        $notification_to_receiver->user_id = $document->contact->userByMobileNumber->id ?? null;
                        $notification_to_receiver->save();

                        $document['global_content'] = $global_setting->content;
                        $document_data[] = $document;
                    }
                }
                if ($document_data) {
                    foreach ($document_data as $contact_data) {
                        $contact = Contact::where('id', $contact_data->contact_id)->first();
                        if ($contact) {
                            $user = User::where('mobile_number', $contact->contact_mobile_number)->first();
                            if ($user) {
                                $user_data = $user->sign_image;
                            }
                        }
                    }
                }
                DB::commit();
                return response()->json([
                    'status' => 1,
                    'message' => "Your document has been updated",
                    'sign_image_path' => URL::to('/') . '/public/storage/uploads/user-sign/',
                    'data' => $document_data,
                    'sign_image' => $user_data,
                ]);
            }
        } catch (\Exception $exception) {
            return error_response($exception);
        }
    }

    public function cancel_document(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_id' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }

        Document::where('id', $request['document_id'])->update(['is_cancel' => 1]);

        $data = Document::where('id', $request['document_id'])->first();

        if ($data->user->push_notification == 1) {
            //for push notification
            $msg_send = array(
                'body' => "Your document has been cancelled",
                'title' => 'go NDA',
                'type' => 'document',
                'document' => $data->id,
                'subtitle' => "Your document has been cancelled",
                'key' => '5',
                'vibrate' => 1,
                'sound' => 1,
                'largeIcon' => 'large_icon',
                'smallIcon' => 'small_icon'
            );
            push_notification_send_driver($data->user->device_token, $msg_send);
            //for push notification
        }
        return response()->json([
            'status' => 1,
            'message' => "Your document has been cancelled",
            'data' => $data,
        ]);
    }

    public function docDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_id' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }

        Document::where('id', $request['document_id'])->delete();
        return response()->json([
            'status' => 1,
            'message' => "Your draft has been Deleted",
        ]);
    }

    public function notarizeChangeWithoutStrip(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_id' => 'required|exists:documents,id',
            'transaction_identifier_id' => 'required',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }
        try {
            $document = Document::with('stateUs:id,name', 'user', 'contact.userByMobileNumber')
                ->where('id', $request['document_id'])->first();

            if ($document->notarize == 0) {

                $document->notarize = 1;
                $document->transaction_identifier_id = $request['transaction_identifier_id'];
                $document->save();

                $user = auth('api')->user();

                if ($document->user->push_notification == 1) {
                    //for push notification
                    $msg_send = array(
                        'body' => 'Your notary request has been sent to support@getgonda.com.',
                        'title' => 'go NDA',
                        'type' => 'document',
                        'document' => $document->id,
                        'subtitle' => 'Your notary request has been sent to support@getgonda.com.',
                        'key' => '5',
                        'vibrate' => 1,
                        'sound' => 1,
                        'largeIcon' => 'large_icon',
                        'smallIcon' => 'small_icon'
                    );
                    push_notification_send_driver($document->user->device_token, $msg_send);
                    //for push notification
                }
                $mail_array = [
                    'first_name' => ucfirst($user['first_name']),
                    'last_name' => ucfirst($user['last_name']),
                    'document_id' => $request['document_id'],
                ];

                $email_template = EmailTemplateManagement::where('slug', 'notary_email')->first();
                $message = str_replace(array('{first_name}', '{last_name}', '{document_id}'), $mail_array, $email_template->content);

                $to_mail = "support@getgonda.com";
                $subject = $email_template->subject;
                $from_mail = env('MAIL_FROM_ADDRESS');

                $data['msg'] = $message;

                Mail::send('admin.emails.notary-change', $data, function ($message) use ($to_mail, $subject, $from_mail) {
                    $message->to($to_mail);
                    $message->subject($subject);
                    $message->from($from_mail);
                });
                $message = 'Your notary request has been sent to support@getgonda.com.';
                $status = 1;
                $document->sender_details = json_decode($document->sender_details);
                $document->receiver_details = json_decode($document->receiver_details);

                //for keyword
                $keyword = [];
                if ($document->keyword_id) {
                    $keyword_details = json_decode($document->keyword_id);
                    if ($keyword_details) {
                        foreach ($keyword_details as $k => $keyword_details_data) {
                            $keyword[$k] = Keyword::where('id', $keyword_details_data->id)->first();
                            $keyword[$k]['answer'] = $keyword_details_data->answer;
                        }
                    }
                } else {
                    $keyword = [];
                }
                $document['keywords'] = $keyword;
                //for keyword
                return response()->json([
                    "status" => $status,
                    "message" => $message,
                    'contact_image_path' => URL::to('/') . '/public/storage/uploads/contact-image/',
                    'profile_image_path' => URL::to('/') . '/public/storage/uploads/user-profile/',
                    'sign_image_path' => URL::to('/') . '/public/storage/uploads/user-sign/',
                    "data" => $document
                ]);
            }
            return response()->json([
                "status" => 0,
                "message" => 'Your notary request has not been sent to support@getgonda.com.',
                "data" => []
            ]);
        } catch (\Exception $exception) {
            return error_response($exception);
        }
    }
}
