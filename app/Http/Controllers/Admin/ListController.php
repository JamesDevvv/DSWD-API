<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\AdminSettings\UserLogAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Admin\ListAction;

class ListController extends Controller
{
    //
    protected $ListAction;

    public function __construct(ListAction $ListAction)
    {
        $this->ListAction = $ListAction;
    }


    public function IncidentLists(Request $request)
    {
        if($request->dromicStatus)
        {
            $data = $this->ListAction->DromicLists($request);
        }else{
            $data = $this->ListAction->IncidentLists($request);
        }

        $status = $data[0];
        $message = $data[1];
        $list = isset( $data[2] ) ? $data[2] : null;

        return response()->json([
            'status' => $status,
            'message' =>  $message,
            'data' => $list,
        ], !$data[0] ? 500 : 200);

    }

    public function ReportsByUser($id, Request $request)
    {
        $data = $this->ListAction->ReportsByUser($id, $request);

        return response()->json([
            'status' => $data[0],
            'message' => $data[1],
            'data' => $data[2],
        ], !$data[0] ? 500 : 200);
    }

    public function ReportsByPercentage($id, Request $request)
    {
        $data = $this->ListAction->ReportsByPercentage($id, $request);

        return response()->json([
            'status' => $data[0],
            'message' => $data[1],
            'data' => $data[2],
        ], !$data[0] ? 500 : 200);
    }


    public function GetReportIncidentDetatils($id)
    {
        $data = $this->ListAction->GetReportIncidentDetatils($id);

        return response()->json([
            'status' => $data[0],
            'message' => $data[1],
            'data' => $data[2],
        ], !$data[0] ? 500 : 200);

    }

    public function AsOfList($incident_code)
    {
        $data = $this->ListAction->AsOfList($incident_code);

        return response()->json([
            'status' => $data[0],
            'message' => $data[1],
            'data' => $data[2],
        ], !$data[0] ? 500 : 200);
    }
    public function GetArchiveDetails($id)
    {
        $data = $this->ListAction->GetArchiveDetails($id);

        return response()->json([
            'status' => $data[0],
            'message' => $data[1],
            'data' => $data[2],
        ], !$data[0] ? 500 : 200);
    }


    public function Index(Request $request)
    {
        $userlog = new UserLogAction;

        $data = $userlog->Index($request);


        return $data;

    }
    public function AugmentationList(Request $request)
    {

        $data = $this->ListAction->AugmentationList($request);


        return $data;

    }

    public function LeadSummary(Request $request)
    {
        $data = $this->ListAction->LeadSummary($request);

        return $data;
    }
}
