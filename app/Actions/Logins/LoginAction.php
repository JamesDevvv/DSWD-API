<?php

namespace App\Actions\Logins;

use App\Actions\Admin\AdminSettings\UserLogAction;
use App\Http\Resources\AdminResource;
use App\Http\Resources\UserResource;
use App\Models\AdminModel;
use App\Models\Reference\OtpModel;
use App\Models\User;
use App\Models\User\TrainingModel;
use App\Models\User\UnverifiedEmailModel;
use Auth;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Facades\Socialite;
use App\Services\OtpService;

class LoginAction
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }
    public function loginAdmin(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $admin = AdminModel::with(['role', 'role.permissions'])->where('email', $request->email)->first();

            if (!$admin) {
                return response()->json(['status' => false, 'message' => 'Admin account not found'], 404);
            }

            if (!Hash::check($request->password, $admin->password)) {
                return response()->json(['status' => false, 'message' => 'Incorrect password'], 401);
            }

            if($admin->status === 'blocked')
            {
                return response()->json(['status' => false, 'message' => 'Your account has been temporarily suspended by the administrator. Please contact support for further assistance.'], 403);
            }

            // ! commented to allow multiple login across devices
            // $lastToken = $admin->tokens()->latest()->first();
            // if ($lastToken) {
            //     $lastToken->update(['expires_at' => now()]);
            // }

            $token = $admin->createToken('adminToken')->plainTextToken;


            $logAction = new UserLogAction;
            $logs = [
                'type' => 'admin',
                'user_id' => $admin->id,
                'activity' => 'Logged in'
            ];
            $logAction->store($logs);

            return response()->json([
                'status' => true,
                'message' => 'Admin logged in successfully',
                'Bearer' => $token,
                'admin' => new AdminResource($admin),
            ], 200);


        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'An error occurred during admin login', 'error' => $e->getMessage()], 500);
        }
    }

    public function logoutAdmin(Request $request)
    {
        try {
            $logAction = new UserLogAction;
            $logs = [
                'type' => 'admin',
                'user_id' => auth()->guard('sanctum')->user()->id,
                'activity' => 'logged out'
            ];
            $logAction->store($logs);

            // Revoke the token that was used to authenticate the current request
            $request->user()->currentAccessToken()->delete();

            return [true, 'Admin logged out successfully'];
        } catch (\Exception $e) {
            return [false, $e->getMessage()];
        }
    }

    public function validateToken(Request $request)
    {
        try {
            $token = auth()->guard('sanctum')->user()->tokens()->latest()->first();

            $query = DB::table('personal_access_tokens')->where('token', $token->token)->first();

            if ($query && (is_null($query->expires_at))) {
                return [true, 'Token is valid'];
            } else {
                return [false, 'Token is invalid or expired'];
            }
        } catch (\Exception $e) {
            return [false, $e->getMessage()];
        }
    }

    public function loginUser(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json(['status' => false, 'message' => 'Account not found. Please sign up or login using Google sign-in'], 404);
            }

            if ($user && $user->provider_id != null) {
                return response()->json(['status' => false, 'message' => 'Your account is already registered using Google sign-in'], 409);
            }
            // di ko muna nsinama yung sa qrt dahil wala pa yung approval sa admin
            if ($user && $user->verified_at == null) {
                return response()->json(['status' => false, 'message' => 'Your account is not validated.'], 403);
            }
            if($user->status === 'blocked')
            {
                return response()->json(['status' => false, 'message' => 'Your account has been temporarily suspended by the administrator. Please contact support for further assistance.'], 403);
            }

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['status' => false, 'message' => 'Invalid credentials'], 401);
            }


            // ! commented to allow multiple login across devices
            // $lastToken = $user->tokens()->latest()->first();
            // if ($lastToken) {
            //     $lastToken->update(['expires_at' => now()]);
            // }

            $token = $user->createToken('userToken')->plainTextToken;

            $logAction = new UserLogAction;
            $logs = [
                'type' => $user->type,
                'user_id' => $user->id,
                'activity' => 'logged in'
            ];
            $logAction->store($logs);

            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully',
                'Bearer' => $token,
                'user' => new UserResource($user),
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function logoutUser(Request $request)
    {
        try {
            $logAction = new UserLogAction;
            $logs = [
                'type' => 'public',
                'user_id' => auth()->guard('sanctum')->user()->id,
                'activity' => 'logged out'
            ];
            $logAction->store($logs);

            // Revoke the token that was used to authenticate the current request
            $request->user()->currentAccessToken()->delete();

            return [true, 'User logged out successfully'];
        } catch (\Exception $e) {
            return [false, $e->getMessage()];
        }
    }

    public function userDetails(Request $request)
    {
        try {
            if (!Auth::check()) {
                return [false, 'User not authenticated'];
            }
            $user = User::with(['avatar'])->where('id', auth('sanctum')->user()->id)->first();
            $data = new UserResource($user);
            return [true, 'success', $data];
        } catch (\Exception $e) {
            return [false, $e->getMessage()];
        }
    }

    public function adminDetails(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['message' => 'Admin not authenticated'], 401);
            }
            $admin = AdminModel::with(['role', 'role.permissions'])->where('id', auth('sanctum')->user()->id)->first();

            $data = new AdminResource($admin);
            return [true, 'success', $data];
        } catch (\Exception $e) {
            return [false, $e->getMessage()];
        }
    }



    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
            $type = 'public';


            $existingUser = User::where('email', $socialUser->email)->first();
            if ($existingUser && $existingUser->provider_id == null) {
                return response()->json(['status' => true, 'message' => 'This email is already registered using email, not Google sign-in.'], 201);
            }


            $user = User::where('provider_id', $socialUser->getId())->first();


            if (!$user) {

                $user = User::create([
                    'type' => $type,
                    'fullname' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                ]);
            }

            if($user->status === 'blocked')
            {
                return response()->json(['status' => false, 'message' => 'Your account has been temporarily suspended by the administrator. Please contact support for further assistance.'], 403);
            }

            Auth::login($user, true);


            $token = $user->createToken('authToken')->plainTextToken;


            $logAction = new UserLogAction;
            $logs = [
                'type' => 'public',
                'user_id' => $user->id,
                'activity' => 'logged in'
            ];
            $logAction->store($logs);


            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully',
                'bearer' => $token,
                'avatar' => $socialUser->getAvatar(),
                'user' => new UserResource($user),
            ], 200);
        } catch (\Exception $e) {

            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }



    public function ChangePassword(Request $request)
    {
        try {
            $data = $request->validate([
                'userId' => 'required|string',
                'password' => 'required|string',
            ]);

            AdminModel::where('id', $data['userId'])->update([
                'password' => Hash::make($data['password']),
            ]);


            $logAction = new UserLogAction;
            $logs = [
                'type' => 'admin',
                'user_id' => auth()->guard('sanctum')->user()->id,
                'activity' => 'changed password'
            ];
            $logAction->store($logs);

            return [true, 'Password changed successfully'];
        } catch (\Exception $e) {
            return [false, $e->getMessage()];
        }
    }

    public function PublicEmailRegistration(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8',
                'fullName' => 'required|string|max:255',
            ]);
            $user = User::where('email', $data['email'])->first();
            if ($user) {
                return response()->json(['status' => true, 'message' => 'Email is already exists.'], 409);
            }
            UnverifiedEmailModel::create([
                'fullname' => $data['fullName'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $this->sendOtp($data['email']);

            return response()->json(['status' => true, 'message' => 'Public email registration successful. Please wait for the OTP to verify.'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function sendOtp($email)
    {

        try {
            $otp = rand(100000, 999999);
            $expiresAt = now()->addMinutes(5);

            OtpModel::create([
                'email' => $email,
                'otp' => $otp,
                'expires_at' => $expiresAt
            ]);

            $this->otpService->sendOtp($email, $otp);

            return response()->json(['status' => true, 'message' => 'OTP sent successfully. Please wait 60 seconds before resending.'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function verifyOtp($request)
    {
        try {
            $data = $request->validate([
                'email' => '',
                'otp' => '',
            ]);


            $otpRecord = OtpModel::where('email', $data['email'])
                ->where('otp', $data['otp'])
                ->where('expires_at', '>', now())
                ->first();

            if ($otpRecord) {

                $userData = UnverifiedEmailModel::where('email', $data['email'])->latest()->first();

                User::create([
                    'type' => 'public',
                    'fullname' => $userData->fullname,
                    'email' => $userData->email,
                    'password' => $userData->password,
                    'verified_at' => Carbon::now(),
                ]);


                UnverifiedEmailModel::where('email', $data['email'])->delete();

                return response()->json(['status' => true, 'message' => 'OTP verified successfully'], 200);
            } else {

                return response()->json(['status' => false, 'message' => 'Invalid or expired OTP'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

}
