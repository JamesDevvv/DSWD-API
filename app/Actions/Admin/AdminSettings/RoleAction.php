<?php

namespace App\Actions\Admin\AdminSettings;
use App\Http\Resources\Reference\RoleResource;
use App\Models\Reference\RoleModel;
use App\Models\Reference\RolePermissionModel;
use DB;
use Exception;

class RoleAction
{
    public function Store($request)
    {
        // ilipat yung logic ng pag save ng roles data
    }

    public function Edit($id, $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'local_goverment_unit' => 'nullable|string|max:255',
            'emergency_operation_center' => 'nullable|string|max:255',
            'regional_director' => 'nullable|string|max:255',
            'local_chief_executive' => 'nullable|string|max:255',
            'permission' => 'nullable',
            'permission.*.feature_name' => 'nullable|string|max:255',
            'permission.*.create' => 'nullable|string|max:255',
            'permission.*.view' => 'nullable|string|max:255',
            'permission.*.modify' => 'nullable|string|max:255',
            'permission.*.delete' => 'nullable|string|max:255',
        ]);

        $roleData = RoleModel::where('id', $id)->first();

        DB::beginTransaction();

        try {
            $roleData->update([
                'name' => $data['name'],
                'description' => $data['description'],
                'local_goverment_unit' => $data['local_goverment_unit'],
                'emergency_operation_center' => $data['emergency_operation_center'],
                'regional_director' => $data['regional_director'],
                'local_chief_executive' => $data['local_chief_executive'],
            ]);

            $permissions = json_decode($data['permission']);


            RolePermissionModel::where('role_id', $roleData->id)->delete();
            foreach ($permissions as $permission) {
                RolePermissionModel::create([
                    'feature_name' => $permission->feature_name,
                    'role_id' => $roleData->id,
                    'create' => $permission->create,
                    'view' => $permission->view,
                    'modify' => $permission->modify,
                    'delete' => $permission->delete,
                ]);
            }



            $logAction = new UserLogAction;
            $logs = [
                'type' => 'admin',
                'user_id' => auth()->guard('sanctum')->user()->id,
                'activity' => 'edit role ' . $roleData->name,
            ];
            $logAction->store($logs);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function Index($request)
    {
        try {
            $query = RoleModel::with('permissions')->latest();

            if ($request->field && $request->search) {
                $query = $query->when($request->field == 'role', function ($query) use ($request) {
                    $query->where('name','like', '%' . $request->search . '%');
                })->when($request->field !== 'role', function ($query) use ($request) {
                    $query->where($request->field, 'like', '%' . $request->search . '%');
                });
            }


            $perPage = $request->per_page ?? 15;
            $data = $query->paginate($perPage);

            $data = RoleResource::collection($data);

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
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function GetInfo($id)
    {
        try {
            $role = RoleModel::with('permissions')->where('id', $id);

            $data = RoleResource::collection($role);
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $data,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
