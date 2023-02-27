<?php

namespace App\Http\Controllers\admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExportController extends Controller
{
    /* file export  */
    public function userExportFile(Request $request)
    {
        // dd($condition);
        // die;
        // $condition = ['role', '<>', 'admin'];
        $userExport = new UsersExport($request->all());
        return Excel::download($userExport, 'users-list.xlsx');
    }
}
