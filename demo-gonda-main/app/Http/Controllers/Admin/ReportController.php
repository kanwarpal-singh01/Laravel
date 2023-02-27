<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Model\MarketingAdManagement;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function userReopts(Request $request){
        $layout_data = [
            'title' => 'User Reports Management',
            'url' => route('admin.user.reports'),
            'icon' => 'fa fa-picture-o'
        ];
        $user = User::with('companyDetail', 'mailingAddress.countries', 'mailingAddress.states')
                        ->where('role', 3);

        if (!empty($request->from) && !empty($request->to)){//dd($request->all());
            $from = Carbon::createFromFormat("Y-m-d", $request->from)->format("Y-m-d");
            $to = Carbon::createFromFormat("Y-m-d", $request->to)->format("Y-m-d");
            $user = $user->whereDate('created_at', '>=', $from)
                        ->whereDate('created_at', '<=', $to);
        }
        $user = $user->paginate(50);

        if ($request->has('user_export')){
            return Excel::download(new UsersExport($user), 'users.xlsx');
        }
        return view('admin.report.user-reports', compact('user', 'layout_data'));
    }

    public function marketing(Request $request){
        $layout_data = [
            'title' => 'Marketing Management',
            'url' => route('admin.marketing'),
            'icon' => 'fa fa-picture-o'
        ];
        $list = MarketingAdManagement::orderBy('created_at', 'desc')->paginate(50);
        return view('admin.report.marketing', compact('list','layout_data' ));
    }
}
