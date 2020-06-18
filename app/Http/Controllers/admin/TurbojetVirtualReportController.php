<?php

namespace App\Http\Controllers\admin;

use App\TurbojetVirtualReport;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TurbojetVirtualReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Turbojet Setting');
    }

    public function index()
    {
        $data=[];
        $data['mainmenu'] = 'Turbojet';
        $data['menu'] = 'Turbojet Virtual Report';
        $data['reports'] = TurbojetVirtualReport::orderBy('created_at', 'desc')->get();
        return view('admin.turbojet-virtual-report.index', $data);
    }

    public function download($id)
    {
        $report = TurbojetVirtualReport::findOrFail($id);
        return response()->file($report->getReportStoragePath());
    }
}
