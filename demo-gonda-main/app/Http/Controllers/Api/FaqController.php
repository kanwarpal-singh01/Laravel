<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\EmailTemplateManagement;
use App\Model\FaqManagement;
use App\Model\GlobalSettingManagement;
use App\Model\NotaryDetail;
use App\Model\SmsContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class FaqController extends Controller
{
    public function list(){
        $faq = FaqManagement::where('status', 1)->get();
        $setting = GlobalSettingManagement::first();
        $email_template = EmailTemplateManagement::where('slug', 'help_for_app')->first();
        $notary_details = NotaryDetail::where('status', 1)->first();
        $sms_content = SmsContent::where('status', 1)->first();
        $sms_data = [];
        $notary_data = [];
        $setting_data = [];
        $faq_data = [];
        $email_template_data = [];
        if (!empty($setting)){
            $setting_data = $setting;
        }
        if (!empty($email_template)){
            $email_template_data = $email_template;
        }
        if (!empty($notary_details)){
            $notary_data = $notary_details;
        }
        if (!empty($sms_content)){
            $sms_data = $sms_content;
        }
        if ($faq->isNotEmpty()){
            foreach ($faq as $data){
                $faq_data[] = $data;
            }
            return response()->json([
                'status' => 1,
                'message' => 'FAQ Records',
                'contact_us_path' => URL::to('/') . '/contact-us',
                'apple_key' => 1,
                'setting_data' => $setting_data,
                'email_template_data' => $email_template_data,
                'notary_details' => $notary_data,
                'sms_content' => $sms_data,
                'faq_data' => $faq_data,
                'STRIPE_KEY' => env('STRIPE_KEY'),
                'STRIPE_SECRET' => env('STRIPE_SECRET')
            ]);
        }
        return response()->json([
            'status' => 1,
            'message' => 'No Records',
            'data' => []
        ]);
    }

}
