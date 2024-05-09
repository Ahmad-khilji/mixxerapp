<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReportRequest;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function report(ReportRequest $request)
    {
          $report = new Report();
        $report->user_id= $request->user_id;
 $report->type = $request->type;
        $report->reported_id = $request->reported_id;
             $report->message = $request->message;
        $report->save();

        return response()->json([
            'status' => true,
            'action' =>  'Report added',
        ]);
    }
}
