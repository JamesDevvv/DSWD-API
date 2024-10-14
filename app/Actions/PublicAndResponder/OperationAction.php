<?php

namespace App\Actions\PublicAndResponder;

use App\Actions\Admin\AdminSettings\UserLogAction;
use App\Http\Requests\ReportRequest;
use App\Models\Responders\CheckInModel;
use App\Models\Responders\ReportModel;
use App\Models\User;
use App\Models\User\TrainingModel;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Intervention\Image\Facades\Image;

class OperationAction
{
    public function CreateReport($request)
    {
        try {


            $data = $request->validated();

            $status = 'pending';
            $created_by = 'public';
            if (isset($data['qrt']) && $data['qrt'] == "true") {
                $status = 'Positive';
                $created_by = 'qrt';
            }


            $existingReport = $this->CheckExistingReport($data);
            if ($existingReport) {
                return $existingReport;
            }

            DB::beginTransaction();
            $incident_code = $this->generateReportCode();
            $report = ReportModel::create([
                'incident_code' => $incident_code,
                'lgu_id' => $data['lgu_id'] ?? null,
                'incident_date' => $data['incident_date'],
                'user_id' => $data['user_id'],
                'start' => $data['start'],
                'created_by' => $created_by,
                'disaster_type' => $data['disaster_type'],
                'longitude' => $data['longitude'],
                'latitude' => $data['latitude'],
                'district_code' => $data['district_code'],
                'municipality_code' => $data['municipality_code'],
                'barangay_code' => $data['barangay_code'],
                'situational_overview' => $data['situational_overview'],
                'status' => $status
            ]);

            if ($request->hasFile('file')) {
                $files = $request->file('file');
                foreach ($files as $file) {
                    if (in_array($file->getClientOriginalExtension(), ['jpeg', 'png', 'jpg', 'gif', 'svg'])) {
                        $file = $this->optimizeImage($file);
                    }
                    $this->attachMedia($report, $file);
                }
            }

            $logAction = new UserLogAction;
            $logs = [
                'type' => auth('sanctum')->user()->type,
                'user_id' => auth()->guard('sanctum')->user()->id,
                'activity' => 'Created new report ' . $incident_code
            ];
            $logAction->store($logs);

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Report created successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function CheckExistingReport($data)
    {
        $today = Carbon::today();
        $latitude = $data['latitude'];
        $longitude = $data['longitude'];
        $radius = 0.025;

        $checkReport = DB::table('reports')
            ->select('*', DB::raw("(
                6371 * acos(
                    cos(radians($latitude)) *
                    cos(radians(latitude)) *
                    cos(radians($longitude) - radians($longitude)) +
                    sin(radians($latitude)) *
                    sin(radians(latitude))
                )
            ) AS distance"))
            ->having('distance', '<=', $radius)
            ->where('disaster_type', $data['disaster_type'])
            ->where('barangay_code', $data['barangay_code'])
            ->whereDate('created_at', $today)
            ->first();

        if ($checkReport) {
            $currentReport = ReportModel::where('incident_code', $checkReport->incident_code)->first();
            $sum = $currentReport->total_report + 1;

            $currentReport->update([
                'total_report' => $sum,
            ]);

            return response()->json(['status' => true, 'message' => 'A report already exists, Thank you for your contribution and concern.'], 201);
        }

        return null;
    }
    private function optimizeImage($file)
    {
        $img = Image::make($file->getRealPath())
            ->orientate() // Adjust orientation based on EXIF data
            ->resize(1280, 720, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

        $tempPath = tempnam(sys_get_temp_dir(), 'optimized_') . '.jpg';

        $img->encode('jpg', 75)->save($tempPath);

        return new \Illuminate\Http\UploadedFile(
            $tempPath,
            $file->getClientOriginalName(),
            'image/jpeg',
            null,
            true
        );
    }
    public function attachMedia(ReportModel $report, $file)
    {
        $report->addMedia($file)
            ->withCustomProperties([
                'original_name' => $file->getClientOriginalName(),
                'extension' => $file->getClientOriginalExtension(),
                'uploaded_by' => auth('sanctum')->user()->fullname,
            ])
            ->toMediaCollection('reports');
    }
    public function generateReportCode()
    {
        $datePart = Carbon::now()->format('m-d-y');
        $lastReport = ReportModel::where('incident_code', 'like', $datePart . '%')
            ->orderBy('incident_code', 'desc')
            ->first();

        if ($lastReport) {
            $lastNumber = intval(substr($lastReport->incident_code, -3));
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $datePart . '-' . $newNumber;
    }
    public function UpdateProfileDetails(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'fullname' => 'nullable|string|max:255',
                'age' => 'nullable|string|max:255',
                'gender' => 'nullable|string|max:255',
                'file.*' => 'file|mimes:jpeg,png,jpg|max:25000'
            ]);

            $user = User::find($id);

            if (!$user) {
                return response()->json(['message' => 'User not found']);
            }

            $user->update([
                'fullname' => $data['fullname'],
                'age' => $data['age'],
                'gender' => $data['gender'],
            ]);

            if ($request->hasFile('file')) {
                $files = $request->file('file');
                foreach ($files as $file) {
                    $this->attachAvatar($user, $file);
                }
            }

            return [
                true,
                'Profile updated successfully',
            ];
        } catch (\Exception $e) {
            return [
                false,
                $e->getMessage(),
            ];
        }
    }

    public function attachAvatar(User $user, $file)
    {
        $user->addMedia($file)
            ->withCustomProperties([
                'original_name' => $file->getClientOriginalName(),
                'extension' => $file->getClientOriginalExtension(),
                'uploaded_by' => auth('sanctum')->user()->fullname,
            ])
            ->toMediaCollection('avatar');
    }


    public function UsersRegistration($request)
    {
        try {

            $data = $request->validate([
                'id_number' => 'required|string|max:255',
                'fullname' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'contact' => 'required|string|max:15',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8',
                'district_code' => 'nullable|string|max:255',
                'municipality_code' => 'nullable|string|max:255',
                'barangay_code' => 'nullable|string|max:255',
                'training' => '',
                'training.*.name' => 'nullable|string|max:255',
                'training.*.type' => 'nullable|string|max:255',
                'training.*.date' => 'nullable|date',
                'training.*.duration' => 'nullable|string|max:255',
                'training.*.location' => 'nullable|string|max:255',
                'training.*.conduct_by' => 'nullable|string|max:255',
                'file.*' => 'file'
            ]);

            $type = 'qrt';

            $user = User::create([
                'type' => $type,
                'id_number' => $data['id_number'],
                'fullname' => $data['fullname'],
                'address' => $data['address'],
                'contact' => $data['contact'],
                'province_code' => $data['district_code'],
                'municipality_code' => $data['municipality_code'],
                'barangay_code' => $data['barangay_code'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);


            if (isset($data['training'])) {
                $array = json_decode($data['training'], true);

                foreach ($array as $training) {
                    $TrainingData = TrainingModel::create([
                        'user_id' => $user->id,
                        'name' => $training['name'],
                        'type' => $training['type'],
                        'date' => $training['date'],
                        'duration' => $training['duration'],
                        'location' => $training['location'],
                        'conduct_by' => $training['conduct_by'],
                    ]);

                    $checkSpacing = strtolower(str_replace(' ', '_', $TrainingData->name));

                    if ($request->file($checkSpacing . '_file')) {
                        $files = $request->file($checkSpacing . '_file');

                        foreach ($files as $file) {
                            $this->trainingFiles($TrainingData, $file);
                        }
                    }
                }

            }

            return [true, 'User registered successfully'];
        } catch (\Exception $e) {
            return [false, $e->getMessage()];
        }
    }


    public function trainingFiles(TrainingModel $TrainingData, $file)
    {
        try {
            $file = $TrainingData->addMedia($file)
                ->withCustomProperties([
                    'original_name' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                ])
                ->toMediaCollection('trainings');
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while attaching media', 'error' => $e->getMessage()], 500);
        }
    }


    public function CheckIn($request)
    {
        try {
            $validatedData = $request->validate([
                'role_id' => 'required',
                'user_id' => 'required',
            ]);

            if (
                CheckInModel::where('user_id', $validatedData['user_id'])
                    ->whereDate('created_at', Carbon::now()->toDateString())
                    ->exists()
            ) {
                return response()->json(['status' => false, 'message' => 'You already checked in today'], 409);
            }

            CheckInModel::create([
                'user_id' => $validatedData['user_id'],
                'role_id' => $validatedData['role_id']
            ]);

            $logAction = new UserLogAction;
            $logs = [
                'type' => 'qrt',
                'user_id' => auth()->guard('sanctum')->user()->id,
                'activity' => 'Checked in'
            ];
            $logAction->store($logs);

            return response()->json(['status' => true, 'message' => 'Check-in successful'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function CheckInValidation($id)
    {
        try {
            $currentDate = Carbon::today();

            $check = CheckInModel::where('user_id', $id)->whereDate('created_at', $currentDate)->first();

            $count = CheckInModel::whereDate('created_at', $currentDate)->count();
            $lists = CheckInModel::with(['UserDetails'])
                ->where('role_id', $check->role_id)
                ->whereDate('created_at', $currentDate) // Filter lists by today's date
                ->where('user_id', '!=', $id) // Exclude the requesting user
                ->latest()
                ->get();

            if ($check) {
                return response()->json([
                    'success' => true,
                    'message' => 'You are already checked in today',
                    'isCheckedIn' => true,
                    'checkInCount' => $count,
                    'lists' => $lists
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => 'You have not checked in today',
                'isCheckedIn' => false,
                'checkInCount' => $count
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while checking the check-in status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function QrtOnDuty(){
        // returns list of qrt on duty 
         try {
            $currentDate = Carbon::today();

            $count = CheckInModel::whereDate('created_at', $currentDate)->count();
            $lists = CheckInModel::with(['UserDetails'])
                ->whereDate('created_at', $currentDate) // Filter lists by today's date
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Success fetching list of QRT on duty',
                'checkInCount' => $count,
                'qrts' => $lists
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching list of QRT on duty',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function ChangePassword($request)
    {
        try {
            $data = $request->validate([
                'userId' => 'required|string',
                'password' => 'required|string',
            ]);

            User::where('id', $data['userId'])->update([
                'password' => Hash::make($data['password']),
            ]);


            $logAction = new UserLogAction;
            $logs = [
                'type' => 'public',
                'user_id' => auth()->guard('sanctum')->user()->id,
                'activity' => 'changed password'
            ];
            $logAction->store($logs);

            return response()->json(['success' => true, 'message' => 'Password changed successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


}
