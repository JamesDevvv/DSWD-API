<?php

namespace App\Http\Controllers\PublicAndResponder;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicAndReponder\ReportResource;
use App\Models\Responders\ReportModel;
use Illuminate\Http\Request;
use App\Actions\PublicAndResponder\OperationAction;
class ResponderController extends Controller
{
    //

    protected $OperationAction;

    public function __construct(OperationAction $OperationAction)
    {
        $this->OperationAction = $OperationAction;
    }


    public function UsersRegistration(Request $request)
    {
        $data = $this->OperationAction->UsersRegistration($request);

        return response()->json([
            'status' => $data[0],
            'message' => $data[1],
        ], !$data[0] ? 500 : 200);
    }


    public function IncidentList(Request $request)
    {

        $data = ReportModel::latest()->paginate();

        if ($request->per_page) {
            $data = ReportModel::latest()->paginate($request->per_page);
        }

        return ReportResource::collection($data);
    }

    public function getIncidentDetails($id)
    {
        $data = ReportModel::with(['files'])->where('report_code', $id)->get();

        return ReportResource::collection($data);
    }

    public function changeStatus(Request $request)
    {
        $data = request()->all();
        $data = ReportModel::where('report_code', $data['report_code'])
            ->update([
                'status' => $data['status']
            ]);

        return response()->json([
            'message' => 'succes',
            'data' => $data
        ]);
    }


    public function CheckIn(Request $request)
    {
        $data = $this->OperationAction->CheckIn($request);

        return $data;
    }

    public function CheckInValidation($id){

        $data = $this->OperationAction->CheckInValidation($id);

        return $data;
    }

    public function QrtOnDuty(){
        $data = $this->OperationAction->QrtOnDuty();

        return $data;
    }

    public function ChangePassword(Request $request)
    {
        $data = $this->OperationAction->ChangePassword($request);

        return $data;
    }

}
