<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Keyword;
use App\Model\TemplateManagement;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\URL;
use Mpdf\Mpdf;

class TemplateController extends Controller
{
    public function list()
    {
        $layout_data = [
            'title' => "Template Management",
            'url' => route('admin.template.list'),
            'icon' => "fa fa-envelope"
        ];
        $list = TemplateManagement::orderBy('created_at', 'desc')->paginate(config('custom-paginate.paginate.number'));;
        return view('admin.template-management.list', compact('layout_data', 'list'));
    }

    public function add(Request $request, $id, $page = null)
    { //dd($request->all());
        $layout_data = [
            'title' => "Template Management",
            'url' => route('admin.template.list'),
            'icon' => "fa fa-envelope"
        ];
        // $keyword = Keyword::where(['status' => 1, 'is_deleted' => 0])->orderBy('created_at', 'asc')->pluck('keyword', 'id');
        $keyword = Keyword::where(['status' => 1, 'is_deleted' => 0])->orderBy('position_order')->get();
        $temp_path = 'public/storage/uploads/pdf/';
        if ($request->isMethod('post')) {
            $request->validate([
                'title' => 'required|max:255',
                'subject' => 'required|max:255',
                'credit_points' => 'required|numeric|min:1',
                'nda_summary' => 'required|string',
                'content' => 'required|string',
                'keyword_id' => 'required|array|min:1',
                'status' => 'required|in:1,0',
            ]);
            $add = new TemplateManagement();
            $add->title = $request['title'];
            $add->subject = $request['subject'];
            $add->content = $request['content'];
            $add->credit_points = $request['credit_points'];
            $add->nda_summary = $request['nda_summary'];
            $add->status = $request['status'];
            $add->keyword_id = implode(',', $request['keyword_id']);
            $add->save();

            $mpdf = new Mpdf(['tempDir' => $temp_path]);
            $mpdf->WriteHTML($add->content);
            $pdf_name = $add->title;
            //$pdf_name = 'NDA-'.$item->id.'.pdf';
            $content_pdf = $mpdf->Output($temp_path . $pdf_name, 'F');
            $pdf_path = asset($temp_path . $pdf_name);
            $add->pdf_name = $pdf_name;
            $add->save();
            toastr()->success('Data successfully updated!');
            return redirect('Kobe/template-management/list?page=' . $page);
        }
        return view('admin.template-management.add', compact('layout_data', 'keyword'));
    }

    public function edit(Request $request, $id, $page = null)
    {
        $layout_data = [
            'title' => "Template Management",
            'url' => route('admin.template.list'),
            'icon' => "fa fa-envelope"
        ];
        $keyword = Keyword::where(['status' => 1, 'is_deleted' => 0])->orderBy('position_order')->get();
        $edit = TemplateManagement::findOrFail(base64_decode($id));
        $temp_path = 'public/storage/uploads/pdf/';
        if ($request->isMethod('post')) {
            $request->validate([
                'title' => 'required|max:255',
                'subject' => 'required|max:255',
                'credit_points' => 'required|numeric|min:1',
                'nda_summary' => 'required|string',
                'content' => 'required|string',
                'keyword_id' => 'required|array',
                'status' => 'required|in:1,0',
            ]);
            $content = $request['content'];
            //remove white space from ck editor
            $cpbody = trim($content);
            // $cpbody = preg_replace("/\<p\>\&nbsp\;\<\/p\>/", "", $cpbody);
            // $cpbody = preg_replace("/\&nbsp\;+/", " ", $cpbody);
            // $cpbody = preg_replace("/\s+/", " ", $cpbody);
            //remove white space from ck editor

            $edit->title = $request['title'];
            $edit->subject = $request['subject'];
            $edit->content = $cpbody;
            $edit->credit_points = $request['credit_points'];
            $edit->nda_summary = $request['nda_summary'];
            $edit->status = $request['status'];
            $edit->keyword_id = implode(',', $request['keyword_id']);

            //            if ($request->has('keyword_id')){
            //                $edit->keyword_id = implode(',', $request['keyword_id']);
            //            }
            //            else{
            //                $edit->keyword_id = null;
            //            }

            $edit->save();

            $mpdf = new Mpdf(['tempDir' => $temp_path]);
            $mpdf->WriteHTML($edit->content);
            $pdf_name = $edit->title;
            //$pdf_name = 'NDA-'.$item->id.'.pdf';
            $content_pdf = $mpdf->Output($temp_path . $pdf_name, 'F');
            $pdf_path = asset($temp_path . $pdf_name);
            $edit->pdf_name = $pdf_name;
            $edit->save();

            toastr()->success('Data successfully updated!');
            return redirect('Kobe/template-management/list?page=' . $page);
        }
        return view('admin.template-management.edit', compact('layout_data', 'edit', 'keyword'));
    }

    public function view(Request $request, $id)
    {
        $layout_data = [
            'title' => "Template Management",
            'url' => route('admin.template.list'),
            'icon' => "fa fa-envelope"
        ];
        $view = TemplateManagement::findOrFail(base64_decode($id));
        $keyword = [];
        if ($view->keyword_id) {
            $keyword_id = explode(',', $view->keyword_id);
            $keyword = Keyword::whereIn('id', $keyword_id)->get();
        }
        return view('admin.template-management.view', compact('layout_data', 'view', 'keyword'));
    }

    public function previewForTemplate(Request $request)
    {
        $content = $request['content'];

        $milliseconds = round(microtime(true) * 1000);

        $cpbody = trim($content);
        $cpbody = preg_replace("/\<p\>\&nbsp\;\<\/p\>/", "", $cpbody);
        $cpbody = preg_replace("/\&nbsp\;+/", " ", $cpbody);
        $cpbody = preg_replace("/\s+/", " ", $cpbody);
        $html = '<!DOCTYPE html>
                 <html>
                 <body>';
        $html .= $cpbody;
        $html .= '</body>
                </html>';
        $temp_path = 'public/storage/uploads/view-pdf/';
        $pdf_name = "view-" . $milliseconds . ".pdf";
        $mpdf = new mPDF(['tempDir' => $temp_path]);
        $mpdf->WriteHTML($html);
        $mpdf->Output($temp_path . $pdf_name, 'F');
        $path = asset($temp_path . $pdf_name);
        echo $path;
    }
}
