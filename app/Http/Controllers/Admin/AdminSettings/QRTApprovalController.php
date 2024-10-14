<?php

namespace App\Http\Controllers\Admin\AdminSettings;

use App\Actions\Admin\AdminSettings\QrtApprovalAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QRTApprovalController extends Controller
{
    //
    protected $QRTAction;
    public function __construct(QrtApprovalAction $QRTAction)
    {
        $this->QRTAction = $QRTAction;
    }

    public function Lists(Request $request)
    {

        $data = $this->QRTAction->Lists($request);

        return $data;
    }
    public function QrtDetails($id)
    {
        $data = $this->QRTAction->QrtDetails($id);

        return $data;
    }
    public function ApproveOrReject($id, Request $request)
    {
        $data = $this->QRTAction->ApproveOrReject($id, $request);


        return $data;
    }
}
