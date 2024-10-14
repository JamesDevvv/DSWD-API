<?php

namespace App\Actions\Admin\AdminSettings;

use App\Http\Resources\Customize\UserlogResource;
use App\Models\UserLogModel;
use DB;
use Exception;
use phpseclib3\File\ASN1\Maps\PublicKeyInfo;

class UserLogAction
{
    public function store($logs)
    {
        DB::beginTransaction();
        try {
            UserLogModel::create([
                'type' => $logs['type'],
                'user_id' => $logs['user_id'],
                'activity' => $logs['activity'],
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function Index($request)
    {
        try {
            $query = UserLogModel::with([
                'admin',
                'qrt_public' => function ($q) use ($request) {
                    $q->where('type', $request->type);
                }
            ])->latest();


            if ($request->fields && $request->search) {

                $query = $query->when($request->fields === 'name', function ($query) use ($request) {

                    $query->with([
                        'admin' => function ($q) use ($request) {
                            $q->whereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ['%' . $request->search . '%']);
                        },
                        'qrt_public' => function ($q) use ($request) {
                            $q->where('fullname', 'like', '%' . $request->search . '%');
                        },
                    ]);
                })->when($request->fields === 'office' || $request->fields === 'team', function ($query) use ($request) {

                    $query->with([
                        'admin' => function ($q) use ($request) {
                            $q->where('office', 'like', '%' . $request->search . '%');
                        },
                        'qrt_public' => function ($q) use ($request) {
                            $q->where('team', 'like', '%' . $request->search . '%');
                        },
                    ]);
                });

            }

            $query->where('type', $request->type);
            $perPage = $request->per_page ?? 15;
            $data = $query->paginate($perPage);

            $data = UserlogResource::collection($data);
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
            return response()->json(['status' => false, 'error' => $e->getMessage()]);
        }
    }

}
