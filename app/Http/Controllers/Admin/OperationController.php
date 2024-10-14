<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateReportRequests;
use Illuminate\Http\Request;
use App\Actions\Admin\OperationAction;

class OperationController extends Controller
{
    //
    protected $OperationAction;

    public function __construct(OperationAction $OperationAction)
    {
        $this->OperationAction = $OperationAction;
    }

    public function CreateAdminUsers(Request $request)
    {
        $data = $this->OperationAction->CreateAdminUser($request);

        return response()->json([
            'status' => $data[0],
            'message' => $data[1],
        ], !$data[0] ? 500 : 200);
    }

    public function CreateRoles(Request $request)
    {
        $data = $this->OperationAction->CreateRoles($request);

        return response()->json([
            'status' => $data[0],
            'message' => $data[1],
        ], !$data[0] ? 500 : 200);

    }

    public function UpdateStatusIncident(Request $request, $id)
    {
        $data = $this->OperationAction->UpdateStatusIncident($request, $id);

        return response()->json([
            'status' => $data[0],
            'message' => $data[1],
        ], !$data[0] ? 500 : 200);

    }


    public function CreateReportIncident(UpdateReportRequests $request)
    {
        $data = $this->OperationAction->CreateReportIncident($request);

        return response()->json([
            'status' => $data[0],
            'message' => $data[1],
        ], !$data[0] ? 500 : 200);
    }
    public function UpdateReport(UpdateReportRequests $request)
    {

        $data = $this->OperationAction->UpdateReport($request);

        return response()->json([
            'status' => $data[0],
            'message' => $data[1],
        ], !$data[0] ? 500 : 200);
    }





    public function Forward2LGU(Request $request)
    {
        $data = $this->OperationAction->Forward2LGU($request);

        return response()->json([
            'status' => $data[0],
            'message' => $data[1],
        ], !$data[0] ? 500 : 200);
    }

    public function IsRead($id)
    {
        $data = $this->OperationAction->IsRead($id);

        return response()->json([
            'status' => $data[0],
            'message' => $data[1],
        ], !$data[0] ? 500 : 200);
    }

    public function NotificationList(Request $request)
    {
        $data = $this->OperationAction->NotificationList($request);

        return $data;
    }

    public function ApprovedAugmentation($id,Request $request)
    {
        $data = $this->OperationAction->ApprovedAugmentation($id,$request);


        return $data;
    }

    public function UpdateDromicStatus($id , Request $request)
    {
        $data = $this->OperationAction->UpdateDromicStatus($id,$request);

        return $data;
    }

    public function EditProfile($id,Request $request)
    {
        $data = $this->OperationAction->EditProfile($id,$request);

        return $data;
    }
}
