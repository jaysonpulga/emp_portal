<?php

namespace App\Http\Controllers;

use App\Exports\DailyMovementTrackerExport;
use App\Http\Requests\DownloadDmtRequest;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class DownloadDmtController extends Controller
{
    //
    public function index() {

        $work_date = Carbon::Now()->format('Y-m-d');

        return view('admin.downloaddmt', compact('work_date'));
    }
    public function getdmt(DownloadDmtRequest $request) {

        $work_date = $request->work_date;


        $currDateTime = Carbon::now()->format('Y-m-d H:i:s');

        return Excel::download(new DailyMovementTrackerExport($work_date), 'DMT' . '_' . $currDateTime . '.xlsx');
    }
}
