<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Admin\DashboardAction;
class DashBoardController extends Controller
{
    //
    protected $DashboardAction;

    public function __construct(DashboardAction $DashboardAction)
    {
        $this->DashboardAction = $DashboardAction;
    }

    public function SummarizeStatusCounts(Request $request)
    {
        $data = $this->DashboardAction->SummarizeStatusCounts($request);
        $status = $data[0];
        $message = $data[1];
        $pending = isset( $data[2] ) ? $data[2] : null;
        $positive = isset( $data[3] ) ? $data[3] : null;
        $negative = isset( $data[4] ) ? $data[4] : null;
        $monitored = isset( $data[5] ) ? $data[5] : null;



        return response()->json([
            'status' => $status,
            'message' => $message,
            'pending' => $pending,
            'positive' => $positive,
            'negative' => $negative,
            'monitored' => $monitored,
        ], !$data[0] ? 500 : 200);
    }
    public function MapsData(Request $request){

        $data = $this->DashboardAction->MapsData($request);
        $status = $data[0];
        $message = $data[1];
        $pending = isset( $data[2] ) ? $data[2] : null;
        $positive = isset( $data[3] ) ? $data[3] : null;

        return response()->json([
            'status'=> $status,
            'message'=> $message,
            'pending'=> $pending,
            'positive'=> $positive,
        ], !$data[0] ? 500: 200);
    }

    public function FieldStaff(Request $request)
    {
        $data = $this->DashboardAction->FieldStaff($request);

        return response()->json([
            'status'=>$data[0],
            'message'=>$data[1],
            'data'=>$data[2]
        ]);
    }
    public function FieldStaffList(Request $request)
    {
        $data = $this->DashboardAction->FieldStaff($request);

        return response()->json([
            'status'=>$data[0],
            'message'=>$data[1],
            'data'=>$data[2]
        ]);
    }
}
