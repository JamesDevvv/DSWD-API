<?php

namespace App\Http\Controllers\Admin\AdminSettings;

use App\Actions\Admin\AdminSettings\RoleAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleManagementController extends Controller
{
    //

    protected $RoleAction;
    public function __construct(RoleAction $RoleAction)
    {
        $this->RoleAction = $RoleAction;
    }
    public function Index(Request $request)
    {
        $data = $this->RoleAction->Index($request);

        return $data;
    }

    public function GetInfo($id)
    {
        $data = $this->RoleAction->GetInfo($id);

        return $data;
    }
    public function Edit($id,Request $request)
    {
        $data = $this->RoleAction->Edit($id,$request);

        return $data;
    }
}
