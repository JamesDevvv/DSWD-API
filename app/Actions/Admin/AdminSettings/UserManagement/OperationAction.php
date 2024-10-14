<?php

namespace App\Actions\Admin\AdminSettings\UserManagement;

use App\Actions\Admin\AdminSettings\UserLogAction;
use App\Models\AdminModel;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use Response;

class OperationAction
{
    public function Store($request)
    {
        // ilipat yung fucntion na creating accounts ng admin
    }

    public function Edit($type, $id, $request)
    {
        try {

            if ($type == 'admin') {
                $data = $this->EditAdmin($id, $request);
            } elseif ($type == 'qrt') {
                $data = $this->EditQrt($id, $request);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid type specified'
                ], 400);
            }

            if ($data instanceof \Illuminate\Http\JsonResponse) {
                return $data;
            }

            $logAction = new UserLogAction;
            $logs = [
                'type' => 'admin',
                'user_id' => auth('sanctum')->user()->id,
                'activity' => 'Edited account of ' . ($data->firstname . ' ' . $data->lastname ?? $data->fullname),
            ];
            $logAction->store($logs);

            return response()->json([
                'status' => true,
                'message' => 'User details updated successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }

    }


    public function EditAdmin($id, $request)
    {

        $data = $request->validate([
            'role_id' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'office' => 'required',
            'division' => 'required',
            'service' => 'required',
            'group' => 'required',
            'sub_role_id' => 'nullable'
        ]);


        $details = AdminModel::where('id', $id)->first();


        if (!$details) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }


        $details->update([
            'role_id' => $data['role_id'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'office' => $data['office'],
            'division' => $data['division'],
            'service' => $data['service'],
            'group' => $data['group'],
            'sub_role_id' => $data['sub_role_id'],
        ]);

        return $details;
    }
    public function EditQrt($id, $request)
    {
        $data = $request->validate([
            'fullname' => 'required',
            'team' => 'required',

        ]);

        $details = User::where('id', $id)->first();

        if (!$details) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $details->update([
            'fullname' => $data['fullname'],
            'team' => $data['team'],
        ]);

        return $details;
    }
    public function GetDetails($type,$id)
    {
        try {
            switch ($type) {
                case 'admin':
                    $data = $this->AdminDetails($id);
                    break;

                case 'qrt':
                    $data = $this->QrtDetails($id);
                    break;
                default:
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid type specified'
                    ], 400);
            }

            if ($data instanceof \Illuminate\Http\JsonResponse) {
                return $data;
            }

            return response()->json([
                'status' => true,
                'message' => 'User details fetched successfully',
                'data' => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching user details'
            ], 500);
        }
    }

    public function AdminDetails($id)
    {
        $data = AdminModel::with(['role','sub_role'])->where('id', $id)->get();
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }
        return $data;

    }
    public function QrtDetails($id)
    {
        $data = User::where('id', $id)->get();
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        return $data;

    }


    public function ToggleBlock($type, $id, $request)
    {
        try {
            switch ($type) {
                case 'admin':
                    $data = $this->BlockAdmin($id, $request);
                    break;

                case 'qrt' || 'public':
                    $data = $this->BlockQrtPublic($id, $request);
                    break;

                default:
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid type specified'
                    ], 400);
            }
            if ($data instanceof \Illuminate\Http\JsonResponse) {
                return $data;
            }

            return response()->json([
                'status' => true,
                'message' => 'Block toggled successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while toggling block'
            ], 500);
        }
    }

    public function BlockAdmin($id, $request)
    {

        $details = AdminModel::where('id', $id)->first();

        if (!$details) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $details->update([
            'status' => $request->status
        ]);

        return;


    }

    public function BlockQrtPublic($id, $request)
    {

        $details = User::where('id', $id)->first();


        if (!$details) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $details->update([
            'status' => $request->status
        ]);

        return;


    }


    public function ResetPassword($type, $id)
    {
        try {
            if ($type === 'admin') {
                $data = $this->ResetAdmin($id);
            }

            if ($type === 'public') {
                $data = $this->ResetQrt($id);
            }




            if ($data instanceof \Illuminate\Http\JsonResponse) {
                return $data;
            }


            return response()->json([
                'status' => true,
                'message' => 'Password has been reset successfully'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while resetting the password',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function ResetAdmin($id)
    {
        $default = 'dswd' . now()->year;
        $details = AdminModel::where('id',$id)->first();


        if (!$details) {
            return response()->json([
                'status' => false,
                'message' => 'Account not found'
            ], 404);
        }

        $details->update([
            'password' => Hash::make($default)
        ]);

        return;
    }
    public function ResetQrt($id)
    {
        $default = 'dswd' . now()->year;
        $details = User::where('id',$id)->first();

        if (!$details) {
            return response()->json([
                'status' => false,
                'message' => 'Account not found'
            ], 404);
        }

        $details->update([
            'password' => Hash::make($default)
        ]);

        return;
    }

}
