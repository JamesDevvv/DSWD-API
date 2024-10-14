<?php

namespace App\Actions\Admin;

use App\Http\Resources\AdminResource;
use App\Http\Resources\EOCLGU\AsOfResource;
use App\Http\Resources\EOCLGU\AugmentationResource;
use App\Http\Resources\PublicAndReponder\ReportResource;
use App\Models\AdminModel;
use App\Models\EOCLGU\AugmentationModel;
use App\Models\EOCLGU\InsideModel;
use App\Models\EOCLGU\ReportArchiveModel;
use App\Models\Reference\NotificationModel;
use App\Models\Reference\RoleModel;
use App\Models\Responders\ReportModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ListAction
{


    public function IncidentLists(Request $request)
    {
        try {
            $query = ReportModel::with([
                'district',
                'municipality',
                'barangay',
                'user',
                'admin',
                'lgu',
                'disaggregated',
                'evacuation' => function ($q) {
                    $q->whereNull('archived_id');
                },
                'evacuation.inside',
                'evacuation.outside',
                'augmentation_files' => function ($q) {
                    $q->where('collection_name', 'augmentation');
                },
                'files',
            ])->latest();

            if ($request->role_id != 'null') {
                $query = $query->where('lgu_id', $request->role_id);
            }

            if ($request->disaster_type) {
                $query = $query->where('disaster_type', $request->disaster_type);
            }
            if ($request->district_code) {
                $query = $query->where('district_code', $request->district_code);
            }
            if ($request->search) {
                $searchTerm = '%' . $request->search . '%';
                $query = $query->where(function ($query) use ($searchTerm) {
                    $query->where('incident_code', 'like', $searchTerm)
                        ->orWhere('disaster_type', 'like', $searchTerm)
                        ->orWhereHas('barangay', function ($q) use ($searchTerm) {
                            $q->where('name', 'like', $searchTerm);
                        })
                        ->orWhereHas('lgu', function ($q) use ($searchTerm) {
                            $q->where('name', 'like', $searchTerm);
                        });
                });
            }


            if ($request->status && $request->status != '') {
                $query->where('status', $request->status);
            }

            if ($request->dromicStatus && $request->dromicStatus !== 'null') {
                $query->where('dromic_status', $request->dromicStatus);
            }




            $perPage = $request->per_page ?? 15;
            $data = $query->paginate($perPage);

            $data = new ReportResource($data);

            return [true, 'success', $data];
        } catch (Exception $e) {
            return [false, $e->getMessage()];
        }
    }

    public function ReportsByUser($id, Request $request)
    {
        try {
            $query = ReportModel::with([
                'district',
                'municipality',
                'barangay',
            ])
                ->where('user_id', $id)
                ->where('created_by', 'public')
                ->latest();

            $perPage = $request->per_page ?? 15;
            $data = $query->paginate($perPage);

            $data->getCollection()->transform(function ($report) {
                return [
                    'incident' => $report->incident_code,
                    'disaster_type' => ucfirst($report->disaster_type),
                    'created_at' => $report->created_at->format('m/d/y'),
                    'status' => ucfirst($report->status),
                    'address' => $report->barangay->name . ', ' . ($report->municipality ? $report->municipality->name . ', ' : '') . $report->district->name,
                ];
            });

            $data = new ReportResource($data);

            return [true, 'success', ['total_reports' => $data->total(), 'reports' => $data]];
        } catch (Exception $e) {
            return [false, $e->getMessage()];
        }
    }

    public function ReportsByPercentage($id, Request $request)
    {
        try {
            // if params.base = "0" return error message
            if ($request->base == "0") {
                return [false, 'error', 'Base is required'];
            }

            $data = ReportModel::where('user_id', $id)
                               ->where('created_by', 'public')
                               ->get();

            $base = $request->base;

            $fireReports = $data->where('disaster_type', 'fire')->count();
            $firePercentage = min($fireReports > 0 ? ($fireReports / $base) * 100 : 0, 100);

            $floodReports = $data->where('disaster_type', 'flood')->count();
            $floodPercentage = min($floodReports > 0 ? ($floodReports / $base) * 100 : 0, 100);

            $earthquakeReports = $data->where('disaster_type', 'earthquake')->count();
            $earthquakePercentage = min($earthquakeReports > 0 ? ($earthquakeReports / $base) * 100 : 0, 100);

            $landslideReports = $data->where('disaster_type', 'landslide')->count();
            $landslidePercentage = min($landslideReports > 0 ? ($landslideReports / $base) * 100 : 0, 100);

            $otherReports = $data->where('disaster_type', 'other')->count();
            $otherPercentage = min($otherReports > 0 ? ($otherReports / $base) * 100 : 0, 100);

            return [true, 'success', [
                'total_reports' => $data->count(),
                'fire' => ['percentage' => $firePercentage, 'total' => $fireReports],
                'flood' => ['percentage' => $floodPercentage, 'total' => $floodReports],
                'earthquake' => ['percentage' => $earthquakePercentage, 'total' => $earthquakeReports],
                'landslide' => ['percentage' => $landslidePercentage, 'total' => $landslideReports],
                'other' => ['percentage' => $otherPercentage, 'total' => $otherReports],
            ]];
        } catch (Exception $e) {
            return [false, $e->getMessage()];
        }
    }

    public function DromicLists(Request $request)
    {
        try {
            $query = ReportModel::with([
                'district',
                'municipality',
                'barangay',
                'user',
                'admin',
                'lgu',
                'disaggregated',
                'evacuation' => function ($q) {
                    $q->where('archived_id', 'null');
                },
                'evacuation.inside',
                'evacuation.outside',
                'augmentation_files' => function ($q) {
                    $q->where('collection_name', 'augmentation');
                },
                'files',
            ])->latest();


            if ($request->dromicStatus && $request->dromicStatus !== 'null') {
                $query->where('dromic_status', $request->dromicStatus);
            }

            if ($request->role_id && $request->role_id !== 'null') {
                $query->where('lgu_id', $request->role_id);
            }

            $perPage = $request->per_page ?? 15;
            $data = $query->paginate($perPage);

            $data = new ReportResource($data);

            return [true, 'success', $data];
        } catch (Exception $e) {
            return [false, $e->getMessage()];
        }
    }

    public function LeadSummary(Request $request){
        try {
            $lguId = $request->lgu_id;
            $augmentationQuery = AugmentationModel::with(['files'])->where('status', 'for-rd-approval');
            $dromicQuery = ReportModel::where('dromic_status', 'for-rd-approval');

            if ($lguId !== 'null' && $lguId) {
                $augmentationQuery->where('lgu_id', $lguId);
                $dromicQuery->where('lgu_id', $lguId);
            }

            $augmentation = $augmentationQuery->count();
            $dromic = $dromicQuery->count();

            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => [
                    'total_augmentation' => $augmentation,
                    'total_dromic' => $dromic
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function GetReportIncidentDetatils($id)
    {
        try {
            $report = ReportModel::with([
                'district',
                'municipality',
                'barangay',
                'user',
                'admin',
                'lgu',
                'disaggregated',
                'evacuation' => function ($q) {
                    $q->whereNull('archived_id');
                },
                'evacuation.inside',
                'evacuation.outside',
                'augmentation_files' => function ($q) {
                    $q->where('collection_name', 'augmentation');
                },
                'files',
            ])->where('id', $id)->first();



            return [true, 'Success', $report];
        } catch (Exception $e) {

            return [false, 'error', $e->getMessage()];
        }
    }

    public function AsOfList($incident_code)
    {
        try {
            $data = ReportArchiveModel::where('incident_code', $incident_code)->get();
            $data = AsOfResource::collection($data);
            return [true, 'success', $data];
        } catch (Exception $e) {
            return [false, 'failed', $data];
        }
    }
    public function ArchiveDetails($id)
    {
        try {
            $data = ReportArchiveModel::where('id', $id)->get();
            $data = AsOfResource::collection($data);
            return [true, 'success', $data];
        } catch (Exception $e) {
            return [false, 'failed', $data];
        }
    }

    public function GetArchiveDetails($id)
    {
        try {

            $report = ReportArchiveModel::where('id', $id)
                ->first(['created_at']);

            $createdAt = $report->created_at;

            $data = ReportArchiveModel::with([
                'district',
                'municipality',
                'barangay',
                'user',
                'admin',
                'lgu',
                'disaggregated',
                'evacuation',
                'evacuation.inside',
                'evacuation.outside',
                'augmentation_files' => function ($q) {
                    $q->where('collection_name', 'augmentation');
                },
                'files' => function ($q) use ($createdAt) {
                    $q->where('collection_name', 'reports')
                        ->where('created_at', '<=',$createdAt);
                },
            ])
                ->whereHas('files', function ($q) use ($createdAt) {
                    $q->where('collection_name', 'reports')
                        ->where('created_at','<=', $createdAt);
                })
                ->where('id', $id)
                ->get();

            return [true, 'success', $data];
        } catch (Exception $e) {
            return [false, 'failed', $data];
        }
    }

    public function AugmentationList($request)
    {
        try {
            $validatedData = $request->validate([
                'status' => 'required'
            ]);

            $query = AugmentationModel::with(['files'])->where('status', $validatedData['status'])->latest();

            $perPage = $request->per_page ?? 15;
            $data = $query->paginate($perPage);

            $data = new AugmentationResource($data);

            return [
                'status' => true,
                'message' => 'success',
                'data' => $data,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'failed',
                'error' => $e->getMessage(),
            ];
        }
    }
}
