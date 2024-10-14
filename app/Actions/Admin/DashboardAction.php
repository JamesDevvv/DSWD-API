<?php

namespace App\Actions\Admin;

use App\Models\Responders\CheckInModel;
use App\Models\Responders\ReportModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class DashboardAction
{
    public function SummarizeStatusCounts(Request $request)
    {

        try {

            $pendingQuery = ReportModel::where('status', 'pending');


            $positiveQuery = ReportModel::where('status', 'positive');


            $negativeQuery = ReportModel::where('status', 'negative');


            if ($request->role_id !== 'null') {
                $pendingQuery->where('lgu_id', $request->role_id);
                $positiveQuery->where('lgu_id', $request->role_id);
                $negativeQuery->where('lgu_id', $request->role_id);
            }

            $pending = $pendingQuery->count();
            $positive = $positiveQuery->count();
            $negative = $negativeQuery->count();
            $monitored = $positive + $negative;

            return [
                true,
                'succes',
                $pending,
                $positive,
                $negative,
                $monitored
            ];
        } catch (Exception $e) {
            return [false, $e->getMessage()];
        }
    }
    public function MapsData(Request $request)
    {
        try {


            $pendingQuery = ReportModel::with([
                'district',
                'municipality',
                'barangay',
            ])->where('status', 'pending');

            $positiveQuery = ReportModel::with(
                'district',
                'municipality',
                'barangay',
            )->where('status', 'positive');

            if ($request->role_id !== 'null') {
                $pendingQuery->where('lgu_id', $request->role_id);
                $positiveQuery->where('lgu_id', $request->role_id);
            }

            if ($request->disaster_type) {
                $pendingQuery->where('disaster_type', $request->disaster_type);
                $positiveQuery->where('disaster_type', $request->disaster_type);
            }

            if ($request->district_code) {
                $pendingQuery->where('district_code', $request->district_code);
                $positiveQuery->where('district_code', $request->district_code);
            }

            $pending = $pendingQuery->get();
            $positive = $positiveQuery->get();

            return [
                true,
                'succes',
                $pending,
                $positive,
            ];
        } catch (Exception $e) {
            return [false, $e->getMessage()];
        }
    }

    public function FieldStaff($request)
    {
        try {
            $query = CheckInModel::where('created_at', Carbon::now()->toDateString());
            if($request->role_id) {
                $query->where('role_id', $request->role_id);
            }
            $data = $query->count();
            return [true, 'success', $data];
        } catch (Exception $e) {
            return [false, 'failed', $e->getMessage()];
        }
    }
    public function FieldStaffList($request)
    {
        try {
            $query = CheckInModel::with(['UserDetails'])->where('created_at', Carbon::now()->toDateString());
            if($request->role_id) {
                $query->where('role_id', $request->role_id);
            }
            $data = $query->get();
            return [true, 'success', $data];
        } catch (Exception $e) {
            return [false, 'failed', $e->getMessage()];
        }
    }
}
