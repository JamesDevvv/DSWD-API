<?php

namespace App\Http\Controllers\Reference;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Reference\ReferenceAction;
class ReferenceController extends Controller
{
    //

    protected $ReferenceAction;

    public function __construct(ReferenceAction $ReferenceAction)
    {
        $this->ReferenceAction = $ReferenceAction;
    }
    public function MunicipalityCity(Request $request)
    {
        $data = $this->ReferenceAction->MunicipalityCity($request);
        $status = $data[0];
        $message = $data[1];
        $list = isset( $data[2] ) ? $data[2] : null;

        return response()->json([
            'status' => $status,
            'message' =>  $message,
            'data' => $list,
        ], !$data[0] ? 500 : 200);
    }
    public function CityDistrict($code)
    {
        $data = $this->ReferenceAction->CityDistrict($code);
        $status = $data[0];
        $message = $data[1];
        $list = isset( $data[2] ) ? $data[2] : null;

        return response()->json([
            'status' => $status,
            'message' =>  $message,
            'data' => $list,
        ], !$data[0] ? 500 : 200);
    }
    public function Barangay($code)
    {
        $data = $this->ReferenceAction->Barangay($code);
        $status = $data[0];
        $message = $data[1];
        $list = isset( $data[2] ) ? $data[2] : null;

        return response()->json([
            'status' => $status,
            'message' =>  $message,
            'data' => $list,
        ], !$data[0] ? 500 : 200);
    }
    public function DisasterType(Request $request)
    {
        $data = $this->ReferenceAction->DisasterType($request);
        $status = $data[0];
        $message = $data[1];
        $list = isset( $data[2] ) ? $data[2] : null;

        return response()->json([
            'status' => $status,
            'message' =>  $message,
            'data' => $list,
        ], !$data[0] ? 500 : 200);
    }
    public function getLGU(Request $request)
    {
        $data = $this->ReferenceAction->getLGU($request);
        $status = $data[0];
        $message = $data[1];
        $list = isset( $data[2] ) ? $data[2] : null;

        return response()->json([
            'status' => $status,
            'message' =>  $message,
            'data' => $list,
        ], !$data[0] ? 500 : 200);
    }

    public function lceList(Request $request)
    {
        $data = $this->ReferenceAction->lceList($request);
        $status = $data[0];
        $message = $data[1];
        $list = isset( $data[2] ) ? $data[2] : null;

        return response()->json([
            'status' => $status,
            'message' =>  $message,
            'data' => $list,
        ], !$data[0] ? 500 : 200);
    }
}
