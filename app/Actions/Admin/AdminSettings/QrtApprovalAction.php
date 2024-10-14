<?php

namespace App\Actions\Admin\AdminSettings;

use App\Actions\Reference\ReferenceAction;
use App\Http\Resources\Customize\PublicResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\RejectedEmail;
use Carbon\Carbon;
use Exception;
use Illuminate\Routing\Route;

class QrtApprovalAction
{
    public function Lists($request)
    {
        try {
            $query = User::where('type', 'qrt')->where('status', null)->latest();

            if ($request->fields && $request->search) {

                $query = $query->when($request->fields === 'name', function ($query) use ($request) {

                    $query->where('fullname', 'like', '%' . $request->search . '%');

                })->when($request->fields === 'last_login', function ($query) use ($request) {
                    $query->whereHas('last_login', function ($q) use ($request) {
                        $q->where('last_login', 'like', '%' . $request->search . '%');
                    });

                })->when(!in_array($request->fields, ['name']), function ($query) use ($request) {
                    $query->where($request->fields, 'like', '%' . $request->search . '%');
                });

            }

            $perPage = $request->per_page ?? 15;
            $data = $query->paginate($perPage);

            $data = PublicResource::collection($data);
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $data,
                'pagination' => [
                    'total' => $data->total(),
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                ],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching the data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function QrtDetails($id)
    {
        try {
            $data = User::with(['training_files','training_files.files' =>function ($q){
                $q->where('collection_name','trainings');
            }])->where('id', $id)->get();

            if (!$data) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found'
                ], 404);
            }
            $data = UserResource::collection($data);
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching the data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function ApproveOrReject($id, $request)
    {
        try {
            $Reference = new ReferenceAction;
            $data = $request->validate([
                'status' => 'nullable|string',
                'email' => 'nullable|string',
                'team' => 'nullable|string'
            ]);
            $EmailNotification = New RejectedEmail();
            $UserDetails = User::where('id', $id)->first();

            if (!$UserDetails) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found'
                ], 404);
            }

            if ($data['status'] === 'denied') {
                $EmailNotification->sendRejectEmail($data['email']);
                $UserDetails->delete();
            }

            if ($data['status'] === 'approved') {
                $details = [
                    'type' => 'approval-notif',
                    'sender_type' => auth('sanctum')->user()->type ?? 'admin',
                    'receiver_type' => 'qrt',
                    'recieveId' => $id,
                    'content' => 'Congratulations, your account has been approved. Thank you for registering.'
                ];

                $Reference->pushNotification($details);
            }

            $UserDetails->update([
                'status' => $data['status'],
                'verified_at' => $data['status'] === 'approved' ? now() : null,
                'team' => $data['team']
            ]);

            $logAction = new UserLogAction;
            $logs = [
                'type' => 'admin',
                'user_id' => auth()->guard('sanctum')->user()->id,
                'activity' => 'Account approval for ' . $UserDetails->fullname,
            ];
            $logAction->store($logs);
            return response()->json([
                'status' => true,
                'message' => 'Update successful. Your changes have been saved.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating the data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}


