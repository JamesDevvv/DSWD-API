<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\Actions\Logins\LoginAction;
use Laravel\Socialite\Facades\Socialite;

class UserAdminController extends Controller
{
    //
    protected $LoginAction;

    public function __construct(LoginAction $LoginAction)
    {
        $this->LoginAction = $LoginAction;
    }
    public function loginAdmin(Request $request)
    {
        $data = $this->LoginAction->loginAdmin($request);

        return $data;
    }


    public function logoutAdmin(Request $request)
    {
        $data = $this->LoginAction->logoutAdmin($request);

        return response()->json([
            'status' => $data[0],
            'message' => $data[1],
        ], !$data[0] ? 500 : 200);
    }

    public function validateToken(Request $request)
    {
        $data = $this->LoginAction->validateToken($request);

        return response()->json([
            'status' => $data[0],
            'message' => $data[1],
        ], !$data[0] ? 500 : 200);
    }

    public function loginUser(Request $request)
    {
        $data = $this->LoginAction->loginUser($request);

       return $data;
    }

    public function logoutUser(Request $request)
    {
        $data = $this->LoginAction->logoutUser($request);

        return response()->json([
            'status' => $data[0],
            'message' => $data[1],
        ], !$data[0] ? 500 : 200);
    }

    public function userDetails(Request $request)
    {
        $data = $this->LoginAction->userDetails($request);

        $status = $data[0];
        $message = $data[1];
        $details = isset($data[2]) ? $data[2] : null;


        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $details,
        ], !$status ? 500 : 200);
    }

    public function adminDetails(Request $request)
    {
        $data = $this->LoginAction->adminDetails($request);

        $status = $data[0];
        $message = $data[1];
        $details = isset($data[2]) ? $data[2] : null;


        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $details,
        ], !$status ? 500 : 200);
    }

    public function UsersRegistration(Request $request)
    {
        // Function body emptied
    }



    public function redirectToProvider($provider)
    {
        try {
            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred during redirect to provider', 'error' => $e->getMessage()], 500);
        }
    }

    public function handleProviderCallback($provider)
    {
        $data = $this->LoginAction->handleProviderCallback($provider);

         return $data;
    }

    public function ChangePassword(Request $request)
    {
        $data = $this->LoginAction->ChangePassword($request);

        return response()->json([
            'status' => $data[0],
            'message' => $data[1],
        ], !$data[0] ? 500 : 200);
    }

    public function PublicEmailRegistration(Request $request)
    {
        $data = $this->LoginAction->PublicEmailRegistration($request);

        return $data;
    }

    public function verifyOtp(Request $request)
    {
        $data = $this->LoginAction->verifyOtp($request);

        return $data;
    }

    public function sendOtp($email)
    {
        $data = $this->LoginAction->sendOtp($email);

        return $data;
    }
}
