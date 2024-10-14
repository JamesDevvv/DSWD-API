<?php

namespace App\Http\Controllers\PublicAndResponder;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use Illuminate\Http\Request;
use App\Actions\PublicAndResponder\OperationAction;


class PublicController extends Controller
{
    protected $OperationAction;

    public function __construct(OperationAction $OperationAction)
    {
        $this->OperationAction = $OperationAction;
    }

    public function CreateReport(ReportRequest $request)
    {
        $data = $this->OperationAction->CreateReport($request);

        return $data;
    }

    public function UpdateProfileDetails(Request $request, $id)
    {
        $data = $this->OperationAction->UpdateProfileDetails($request,$id);

        return response()->json([
            'status' => $data[0],
            'message' => $data[1],
        ], !$data[0] ? 500 : 200);
    }



}
