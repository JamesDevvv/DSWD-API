<?php

namespace App\Http\Controllers\Admin\AdminSettings;

use App\Actions\Admin\AdminSettings\UserManagement\ListAction;
use App\Actions\Admin\AdminSettings\UserManagement\OperationAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    //




    public function Lists(ListAction $ListAction, Request $request)
    {
        $data = $ListAction->Lists($request);

        return $data;
    }


    public function Store(OperationAction $operationAction,Request $request)
    {
       // ilipat yung storing ng admin accounts
    }

    public function Edit(OperationAction $OperationAction, $type, $id ,Request $request )
    {
        $data = $OperationAction->Edit($type,$id,$request);

        return $data;
    }
    public function GetDetatils(OperationAction $OperationAction,$type, $id){

        $data = $OperationAction->GetDetails($type, $id);

        return $data;
    }

    public function ToggleBlock(OperationAction $OperationAction,$type, $id , Request $request){
        $data = $OperationAction->ToggleBlock($type,$id,$request);

        return $data;
    }
    public function ResetPassword(OperationAction $OperationAction, $type, $id){
        $data = $OperationAction->ResetPassword($type,$id);

        return $data;
    }
}
