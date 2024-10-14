<?php

namespace App\Actions\Admin\AdminSettings\UserManagement;

use App\Http\Resources\AdminResource;
use App\Http\Resources\Customize\AdminCustomResource;
use App\Http\Resources\Customize\PublicResource;
use App\Http\Resources\Customize\QrtResource;
use App\Http\Resources\UserResource;
use App\Models\AdminModel;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class ListAction
{

    public function Lists(Request $request)
    {
        try {

            switch ($request->type) {
                case 'admin':
                    $data = $this->adminLists($request);
                    break;

                case 'qrt':
                    $data = $this->qrtLists($request);
                    break;

                case 'public':
                    $data = $this->publicLists($request);
                    break;

                default:
                    return [false, 'Invalid type specified'];
            }



            return [
                'status' => true,
                'message' => 'success',
                'data' => $data,
                'pagination' => [
                    'total' => $data->total(),
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                ],
            ];
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage()]);
        }

    }

    public function AdminLists($request)
    {

        $query = AdminModel::with([
            'role',
            'last_login' => function ($q) {
                $q->where('name', 'adminToken')->latest();
            }
        ])->latest();



        if ($request->fields && $request->search) {

            $query = $query->when($request->fields === 'name', function ($query) use ($request) {

                $query->whereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ['%' . $request->search . '%']);

            })
                ->when($request->fields === 'role', function ($query) use ($request) {
                    $query->whereHas('role', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    });

                })->when($request->fields === 'last_login', function ($query) use ($request) {
                    $query->whereHas('last_login', function ($q) use ($request) {
                        $q->where('last_login', 'like', '%' . $request->search . '%');
                    });

                })->when(!in_array($request->fields, ['name', 'role']), function ($query) use ($request) {
                    $query->where($request->fields, 'like', '%' . $request->search . '%');
                });

        }



        $perPage = $request->per_page ?? 15;
        $data = $query->paginate($perPage);
        return AdminCustomResource::collection($data);


    }
    public function QrtLists($request)
    {

        $query = User::with([
            'last_login' => function ($q) {
                $q->where('name', 'userToken')->latest();
            }
        ])->where('type', 'qrt')->where('status', 'approved')->latest();




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
        return QrtResource::collection($data);


    }
    public function PublicLists($request)
    {
        $query = User::with([
            'last_login' => function ($q) {
                $q->where('name', 'userToken')->latest();
            }
        ])->where(function ($query) {
            $query->where('provider_id', '!=', null)
                ->where('verified_at', '=', null);
        })->orWhere(function ($query) {
                $query->where('provider_id', '=', null)
                    ->where('verified_at', '!=', null);
            })->where('type', 'public')
            ->latest();



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
        return PublicResource::collection($data);
    }
}
