<?php

namespace App\Actions\Admin;

use App\Actions\Admin\AdminSettings\UserLogAction;
use App\Actions\Reference\ReferenceAction;
use App\Models\AdminModel;
use App\Models\Core\Media;
use App\Models\EOCLGU\AugmentationModel;
use App\Models\EOCLGU\DisaggregatedModel;
use App\Models\EOCLGU\EvacuationModel;
use App\Models\EOCLGU\InsideModel;
use App\Models\EOCLGU\OutsideModel;
use App\Models\EOCLGU\ReportArchiveModel;
use App\Models\Reference\NotificationModel;
use App\Models\Reference\RoleModel;
use App\Models\Reference\RolePermissionModel;
use App\Models\Responders\CheckInModel;
use App\Models\Responders\ReportModel;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;
use stdClass;
use App\Actions\PublicAndResponder\OperationAction as PublicAction;


class OperationAction
{
    public function CreateAdminUser(Request $request)
    {
        try {
            $data = $request->validate([
                'role_id' => 'required|string|max:255',
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'age' => 'required|string|max:255',
                'birthdate' => 'required|string|max:255',
                'contact' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'office' => 'required|string|max:255',
                'division' => 'required|string|max:255',
                'service' => 'required|string|max:255',
                'group' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:admins,email',
                'sub_role_id' => 'nullable|string'
            ]);
            $year = Carbon::now()->year;
            $defaultPassword = 'dswd' . strval($year);

            AdminModel::create([
                'role_id' => $data['role_id'],
                'sub_role_id' =>$data['sub_role_id'],
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'age' => $data['age'],
                'birthdate' => $data['birthdate'],
                'contact' => $data['contact'],
                'address' => $data['address'],
                'office' => $data['office'],
                'division' => $data['division'],
                'service' => $data['service'],
                'group' => $data['group'],
                'email' => $data['email'],
                'password' => Hash::make($defaultPassword),
            ]);

            return [true, 'Success'];
        } catch (Exception $e) {
            return [false, $e->getMessage()];
        }
    }

    public function CreateRoles(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'required|string|max:255',
            'local_goverment_unit' => 'required|string|max:255',
            'emergency_operation_center' => 'required|string|max:255',
            'regional_director' => 'required|string|max:255',
            'local_chief_executive' => 'required|string|max:255',
            'permission' => 'nullable',
            'permission.*.feature_name' => 'required|string|max:255',
            'permission.*.create' => 'required|string|max:255',
            'permission.*.view' => 'required|string|max:255',
            'permission.*.modify' => 'required|string|max:255',
            'permission.*.delete' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // if duplicate show error
            if (RoleModel::where('name', $data['name'])->exists()) {
                return [false, 'Role name already exists'];
            }

            $role = RoleModel::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'local_goverment_unit' => $data['local_goverment_unit'],
                'emergency_operation_center' => $data['emergency_operation_center'],
                'regional_director' => $data['regional_director'],
                'local_chief_executive' => $data['local_chief_executive'],
            ]);

            $permissions = json_decode($data['permission']);

            foreach ($permissions as $permission) {
                RolePermissionModel::create([
                    'feature_name' => $permission->feature_name,
                    'role_id' => $role->id,
                    'create' => $permission->create,
                    'view' => $permission->view,
                    'modify' => $permission->modify,
                    'delete' => $permission->delete,
                ]);
            }

            DB::commit();
            return [true, 'Success'];
        } catch (Exception $e) {
            DB::rollBack();
            return [false, $e->getMessage()];
        }
    }

    public function UpdateStatusIncident(Request $request, $id)
    {
        try {
            $type = 'update-status';

            ReportModel::where('id', $id)->update([
                'status' => $request->status,
                'validated_at' => Carbon::now()
            ]);

            $incidentData = ReportModel::where('id', $id)->first();

            $this->pushNotification($incidentData, $type);

            return [true, 'Success'];
        } catch (Exception $e) {
            DB::rollBack();
            return [false, $e->getMessage()];
        }
    }


    public function CreateReportIncident($request)
    {
        $PublicAction = new PublicAction();



        try {
            $data = $request->allLower();

            $incident_code = $PublicAction->generateReportCode();
            $created_by = 'eoc-lgu';
            $status = 'positive';
            $progress_status = 'initial';

            if (isset($data['qrt']) && $data['qrt'] == "true") {
                $created_by = 'qrt';
            }

            $info = ReportModel::create([
                'user_id' => $data['user_id'],
                'lgu_id' => $data['role_id'] ?? null,
                'incident_code' => $incident_code,
                'created_by' => $created_by,
                'incident_date' => $data['incident_date'],
                'disaster_type' => $data['disaster_type'],
                'start' => $data['start'],
                'end' => $data['end'],
                'longitude' => $data['longitude'],
                'latitude' => $data['latitude'],
                'district_code' => $data['district_code'],
                'municipality_code' => $data['municipality_code'],
                'barangay_code' => $data['barangay_code'],
                'no_families' => $data['no_families'],
                'no_individual' => $data['no_individual'],
                'dead' => $data['dead'],
                'injured' => $data['injured'],
                'missing' => $data['missing'],
                'residential' => $data['residential'],
                'commercial' => $data['commercial'],
                'mix' => $data['mix'],
                'total_damage' => $data['total_damage'],
                'partial_damage' => $data['partial_damage'],
                'situational_overview' => $data['situational_overview'],
                'remarks' => $data['remarks'] ?? '',
                'augmentation' => $data['augmentation'] ?? '',
                'progress_status' => $progress_status,
                'status' => $status
            ]);



            if (isset($data['disaggregated_data'])) {
                $disaggregated_data = json_decode($data['disaggregated_data']);

                $this->insertDisaggregatedData($disaggregated_data, $info);
            }

            if (isset($data['evacuation'])) {
                $evacuation_data = json_decode($data['evacuation']);
                $info->evacuation_status =  null;
                $this->insertEvacuationData($evacuation_data, $info);
            }

            if ($request->hasFile('file')) {

                $files = $request->file('file');
                foreach ($files as $file) {

                    $this->attachMedia($info, $file);

                }
            }

            if (isset($data['augmentation']) && $request->hasFile('augmentation_file')) {

                $files = $request->file('augmentation_file');
                $this->insertAugmentation($info, augmentation_file: $files);
            }

            $logAction = new UserLogAction;
            $logs = [
                'type' => auth()->guard('sanctum')->user()->type ?? 'admin',
                'user_id' => auth()->guard('sanctum')->user()->id,
                'activity' => 'Created new report' . $incident_code,
            ];
            $logAction->store($logs);





            $request['incident_code'] = $incident_code;
            $request['created_by'] = $created_by;
            $request['status'] = $status;
            $request['progress_status'] = $progress_status;
            $request['file_id'] = $info->id;
            $this->ReportArchived($request);


            return [true, 'Success! Report created successfully.'];
        } catch (Exception $e) {

            return [false, $e->getMessage()];
        }
    }
    public function insertAugmentation($data, $augmentation_file)
    {
        $info = AugmentationModel::create([
            'incident_code' => $data->incident_code,
            'lgu_id' => AdminModel::where('id', $data->user_id)->first()->role_id ?? 'null',
            'remarks' => $data->augmentation,
            'status' => 'for-eoc-approval',
        ]);

        $this->attachAugmentation($info, $augmentation_file);


    }

    public function insertDisaggregatedData($disaggregated_data, $info)
    {
        foreach ($disaggregated_data as $data) {

            DisaggregatedModel::create([
                'info_graphics_id' => $info->id,
                'age' => $data->age,
                'male' => $data->male,
                'female' => $data->female,
            ]);

        }
    }

    public function insertEvacuationData($evacuation_data, $info)
    {

        foreach ($evacuation_data as $evacuation) {

            $evacuationInfo = EvacuationModel::create([
                'incident_code' => $info->incident_code,
                'name' => $evacuation->name,
                'status' => $info->evacuation_status ?? 'archived'
            ]);

            if (isset($evacuation->inside)) {
                $inside = $evacuation->inside;
                $inside = $evacuation->inside;

                InsideModel::create([
                    'evacuation_id' => $evacuationInfo->id,
                    'inside_families' => $inside->families,
                    'inside_individuals' => $inside->individuals,
                ]);
                InsideModel::create([
                    'evacuation_id' => $evacuationInfo->id,
                    'inside_families' => $inside->families,
                    'inside_individuals' => $inside->individuals,
                ]);
            }

            if (isset($evacuation->outside)) {
                $outside = $evacuation->outside;
                OutsideModel::create([
                    'evacuation_id' => $evacuationInfo->id,
                    'outside_families' => $outside->families,
                    'outside_individuals' => $outside->individuals,
                ]);
                OutsideModel::create([
                    'evacuation_id' => $evacuationInfo->id,
                    'outside_families' => $outside->families,
                    'outside_individuals' => $outside->individuals,
                ]);

            }
        }
    }



    public function UpdateReport($request)
    {
        try {
            $data = $request->allLower();
            $info = ReportModel::where('incident_code', $data['incident_code'])->first();
            $progress_status = 'progress';

            if (!$info) {
                return [false, 'data not found!'];
            }


            $info->update([
                'incident_code' => $data['incident_code'],
                'incident_date' => $data['incident_date'],
                'disaster_type' => $data['disaster_type'],
                'start' => $data['start'],
                'end' => $data['end'],
                'longitude' => $data['longitude'],
                'latitude' => $data['latitude'],
                'district_code' => $data['district_code'],
                'municipality_code' => $data['municipality_code'],
                'barangay_code' => $data['barangay_code'],
                'no_families' => $data['no_families'],
                'no_individual' => $data['no_individual'],
                'dead' => $data['dead'],
                'injured' => $data['injured'],
                'missing' => $data['missing'],
                'residential' => $data['residential'],
                'commercial' => $data['commercial'],
                'mix' => $data['mix'],
                'total_damage' => $data['total_damage'],
                'partial_damage' => $data['partial_damage'],
                'situational_overview' => $data['situational_overview'],
                'augmentation' => $data['augmentation'] ?? '',
                'progress_status' => $progress_status,
                'remarks' => $data['remarks'] ?? '',
            ]);

            if (isset($data['disaggregated_data'])) {
                DisaggregatedModel::where('info_graphics_id', $info->id, )->delete();
                $disaggregated_data = json_decode($data['disaggregated_data']);
                $this->insertDisaggregatedData($disaggregated_data, $info);
            }

            if (isset($data['evacuation'])) {
                $evacuations = EvacuationModel::where('incident_code', $info->incident_code)->where('archived_id',null)->first();


                if($evacuations){
                    InsideModel::where('evacuation_id', $evacuations->id)->delete();
                    OutsideModel::where('evacuation_id', $evacuations->id)->delete();
                    $evacuations->delete();
                }


                $evacuation_data = json_decode($data['evacuation']);
                $this->insertEvacuationData($evacuation_data, $info);
            }

            if ($request->hasFile('file')) {
                $files = $request->file('file');
                foreach ($files as $file) {
                    $this->attachMedia($info, $file);
                }
            }

            if (isset($data['augmentation']) && $request->hasFile('augmentation_file')) {

                $files = $request->file('augmentation_file');
                foreach ($files as $file) {
                    if (!$this->checkfileExist($info, $file)) {
                        $this->insertAugmentation($info, $file);
                    }
                }
            }

            $logAction = new UserLogAction;
            $logs = [
                'type' => auth()->guard('sanctum')->user()->type ?? 'admin',
                'user_id' => auth()->guard('sanctum')->user()->id,
                'activity' => 'Update report ' . $info->incident_code,
            ];
            $logAction->store($logs);


            $request['incident_code'] = $data['incident_code'];
            $request['created_by'] = $info->created_by;
            $request['status'] = $info->status;
            $request['role_id'] = $info->lgu_id ?? 'null';
            $request['progress_status'] = $progress_status;
            $request['file_id'] = $info->id;
            $request['lce_id'] = $info->lce_id ?? null;
            $request['dromic_status'] = $info->dromic_status ?? null;
            $this->ReportArchived($request);
            return [true, 'Success'];
        } catch (Exception $e) {
            DB::rollBack();
            return [false, $e->getMessage()];
        }
    }
    public function checkfileExist($info, $files)
    {
        $checkifExist = Media::where([
            ['custom_id', $info->incident_code],
            ['name', $files->getClientOriginalName()],
        ])->exists();

        if ($checkifExist) {
            return true;
        }

        return false;
    }
    public function attachMedia(ReportModel $info, $file)
    {
        try {
            $user = auth('sanctum')->user();
            $info->addMedia($file)
                ->withCustomProperties([
                    'original_name' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                ])
                ->toMediaCollection('reports');
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function attachAugmentation(AugmentationModel $info, $file)
    {
        try {

            if (is_array($file)) {
                $file = $file[0];
            }

            $mediaItem = $info->addMedia($file)
                ->withCustomProperties([
                    'original_name' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                ])
                ->toMediaCollection('augmentation');

            $mediaItem->custom_id = $info->incident_code;
            $mediaItem->save();

        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function Forward2LGU(Request $request)
    {
        try {
            $data = $request->validate([
                'incident_code' => 'required',
                'role_id' => 'required'
            ]);
            $type = 'for-validation';

            $incidentData = ReportModel::where('incident_code', $data['incident_code'])->first();

            if (!$incidentData) {
                return ['message' => 'Incident not found'];
            }

            $incidentData->update([
                'lgu_id' => $data['role_id'],
            ]);


            $this->qrtsms($data['role_id']);

            $this->pushNotification($incidentData, $type);

            $lgu = RoleModel::where('id', $data['role_id'])->first();
            $logAction = new UserLogAction;
            $logs = [
                'type' => auth()->guard('sanctum')->user()->type ?? 'admin',
                'user_id' => auth()->guard('sanctum')->user()->id,
                'activity' => $data['incident_code'] . 'forward to ' . $lgu->name,
            ];
            $logAction->store($logs);

            return [true, 'Success'];
        } catch (Exception $e) {
            DB::rollBack();
            return [false, $e->getMessage()];
        }
    }

    public function qrtsms($roleId)
    {

        $currentDate = Carbon::today();
        $dayOfWeek = $currentDate->format('l');

        $users = CheckInModel::with('UserDetails')
            ->whereHas('UserDetails', function ($q) use ($dayOfWeek) {
                $q->where('team', $dayOfWeek);
            })
            ->whereDate('created_at', $currentDate)
            ->get();

        $lgu = RoleModel::where('id', $roleId)->first();
        $reference = new ReferenceAction();

        if ($users && $lgu) {

            foreach ($users as $user) {

                $data = [
                    'fullname' => $user->UserDetails->fullname,
                    'email' => $user->UserDetails->email,
                    'number' => $user->UserDetails->contact,
                    'lgu_name' => $lgu->name,
                ];

                $reference->sendSms($data);
            }
        }

    }
    public function pushNotification($incidentData, $type)
    {
        try {
            $notification = new ReferenceAction();

            if ($type == 'for-validation') {
                $details = [
                    'type' => $type,
                    'sender_type' => 'admin',
                    'groupRecieveId' => $incidentData->lgu_id ? $incidentData->lgu_id : '',
                    'content' => $incidentData->incident_code,
                ];

            }
            if ($type == 'update-status') {
                $details = [
                    'type' => $type,
                    'sender_type' => auth('sanctum')->user()->type ?? 'admin',
                    'content' => $incidentData->incident_code . ' has been updated to status: ' . $incidentData->status,
                ];

            }
            if ($type == 'augmentation-notif') {
                $details = [
                    'type' => $type,
                    'sender_type' => 'admin',
                    'groupRecieveId' => $incidentData->lgu_id ? $incidentData->lgu_id : '',
                    'content' => 'Your Augmentation Request Has been ' . $incidentData->status,
                ];

            }

            $notification->pushNotification($details);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function ReportArchived($request)
    {
        try {
            DB::beginTransaction();
            $data = $request->allLower();
            $info = ReportArchiveModel::create([
                'user_id' => $data['user_id'],
                'lgu_id' => $data['role_id'] ?? null,
                'incident_code' => $data['incident_code'],
                'created_by' => $data['created_by'],
                'incident_date' => $data['incident_date'],
                'disaster_type' => $data['disaster_type'],
                'start' => $data['start'],
                'end' => $data['end'],
                'longitude' => $data['longitude'],
                'latitude' => $data['latitude'],
                'district_code' => $data['district_code'],
                'municipality_code' => $data['municipality_code'],
                'barangay_code' => $data['barangay_code'],
                'no_families' => $data['no_families'],
                'no_individual' => $data['no_individual'],
                'dead' => $data['dead'],
                'injured' => $data['injured'],
                'missing' => $data['missing'],
                'residential' => $data['residential'],
                'commercial' => $data['commercial'],
                'mix' => $data['mix'],
                'total_damage' => $data['total_damage'],
                'partial_damage' => $data['partial_damage'],
                'situational_overview' => $data['situational_overview'],
                'remarks' => $data['remarks'] ?? '',
                'augmentation' => $data['augmentation'] ?? '',
                'progress_status' => $data['progress_status'],
                'status' => $data['status'],
                'dromic_status' => $data['dromic_status'],
                'lce_id' => $data['lce_id'],
                'file_id' => $data['file_id'],
            ]);

            if (isset($data['disaggregated_data'])) {
                $disaggregated_data = json_decode($data['disaggregated_data']);
                $this->insertArchivedDisaggregatedData($disaggregated_data, $info);
            }
            if (isset($data['evacuation'])) {
                $evacuation_data = json_decode($data['evacuation']);
                $this->insertArchivedEvacuationData($evacuation_data, $info);
            }

            DB::commit();


        } catch (Exception $e) {
            DB::rollBack();
            return [false, $e->getMessage()];
        }
    }



    public function insertArchivedDisaggregatedData($disaggregated_data, $archived_data)
    {

        foreach ($disaggregated_data as $data) {

            DisaggregatedModel::create([
                'archived_id' => $archived_data->id,
                'age' => $data->age,
                'male' => $data->male,
                'female' => $data->female,
            ]);


        }
    }

    public function insertArchivedEvacuationData($evacuation_data, $archived_data)
    {
        foreach ($evacuation_data as $evacuation) {

            $evacuationInfo = EvacuationModel::create([
                'archived_id' => $archived_data->id,
                'incident_code' => $archived_data->incident_code,
                'name' => $evacuation->name,
                'status' => 'archived'
            ]);


            if (isset($evacuation->inside)) {
                $inside = $evacuation->inside;

                InsideModel::create([
                    'evacuation_id' => $evacuationInfo->id ?? $inside->evacuation_id,
                    'inside_families' => $inside->families ?? $inside->inside_families,
                    'inside_individuals' => $inside->individuals ?? $inside->inside_individuals,
                ]);
            }

            if (isset($evacuation->outside)) {
                $outside = $evacuation->outside;
                OutsideModel::create([
                    'evacuation_id' => $evacuationInfo->id ?? $outside->evacuation_id,
                    'outside_families' => $outside->families ?? $outside->outside_families,
                    'outside_individuals' => $outside->individuals ?? $outside->outside_individuals,
                ]);

            }
        }
    }

    public function IsRead($id)
    {
        try {
            NotificationModel::where('id', $id)->update([
                'is_read' => 'true',
            ]);

            return [true, 'Success'];
        } catch (Exception $e) {
            DB::rollBack();
            return [false, $e->getMessage()];
        }
    }
    public function NotificationList(Request $request)
    {
        try {
            $user = auth('sanctum')->user();
            $role = $user->role;
            $id = $user->id;
            $now = Carbon::now();
            $twoDaysAgo = Carbon::now()->subDays(2);

            $notificationQuery = NotificationModel::whereBetween('created_at', [$twoDaysAgo, $now])->latest()->get(); // Fetch the data

            // Loop through each notification
            foreach ($notificationQuery as $notification) {
                // Check the sender_type and load necessary relationships
                if ($notification->sender_type === 'admin') {
                    $notification->load(['from_admin', 'group']);
                } elseif ($notification->sender_type === 'qrt') {
                    $notification->load(['from_qrt', 'group']);
                }
            }

            // Additional query conditions can be applied after if needed
            if ($role->emergency_operation_center == 1) {
                $notificationQuery = $notificationQuery->where('type', 'update-status');
            }

            if ($role->local_goverment_unit == 1) {
                $notificationQuery = $notificationQuery->where('group_recieve_id', $role->id);
            }

            if ($user->type === 'qrt') {
                $notificationQuery = $notificationQuery->where('user_type', 'qrt')->where('recieve_id', $id);
            }
            $notificationQuery = $notificationQuery->values();
            // Return the response
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $notificationQuery
            ], 200);


        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function ApprovedAugmentation($id, $request)
    {
        try {
            $validatedData = $request->validate([
                'status' => 'required'
            ]);

            $query = AugmentationModel::where('id', $id)->first();

            if (!$query) {
                return response()->json([
                    'status' => false,
                    'message' => 'data not found'
                ], 404);
            }

            $query->update([
                'status' => $validatedData['status'],
            ]);

            $type = 'augmentation-notif';

            $this->pushNotification($query, $type);

            return response()->json([
                'status' => true,
                'message' => 'success'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function UpdateDromicStatus($id, $request)
    {
        try {

            $data = $request->validate([
                'dromic_status' => 'nullable',
                'lce_id' => 'nullable'
            ]);

            $query = ReportModel::where('id', $id)->first();

            $dissagrated_data = DisaggregatedModel::where('info_graphics_id', $id)->get();
            $evacuation = EvacuationModel::with(['inside','outside'])->where('incident_code', $query->incident_code)->where('archived_id', null)->get();

            if (!$query) {
                return response()->json([
                    'status' => false,
                    'message' => 'data not found!'
                ], 404);
            }

            $query->update([
                'dromic_status' => $data['dromic_status'],
                'lce_id' => $data['lce_id']
            ]);


            $query->disaggregated_data = $dissagrated_data;
            $query->evacuation = $evacuation;



            $this->ReportArchived($query);

            return response()->json([
                'status' => true,
                'message' => 'success',
                // return dromic_status and lce_id
                'inserted_data' => [
                    'dromic_status' => $query->dromic_status,
                    'lce_id' => $query->lce_id
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function EditProfile($id, $request)
    {
        try {

            $data = $request->validate([
                'firstname' => 'nullable',
                'lastname' => 'nullable',
                'birthday' => 'nullable',
                'contact' => 'nullable',
                'address' => 'nullable',
                'office' => 'nullable',
                'division' => 'nullable',
                'service' => 'nullable',
                'group' => 'nullable',
                'age' => 'nullable'
            ]);

            $query = AdminModel::where('id', $id)->first();


            if (!$query) {

                return response()->json([
                    'status' => false,
                    'message' => 'data not found'
                ], 404);
            }


            $query->update([
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'birthdate' => $data['birthday'],
                'contact' => $data['contact'],
                'address' => $data['address'],
                'office' => $data['office'],
                'division' => $data['division'],
                'service' => $data['service'],
                'group' => $data['group'],
                'age' => $data['age']
            ]);



            return response()->json([
                'status' => true,
                'message' => 'success'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function ReportArchived2($query)
    {
        try {

            DB::beginTransaction();
            $data = $query;
            $fileId = ReportModel::where('incident_code', $data['incident_code'], )->first();
            $info = ReportArchiveModel::create([
                'user_id' => $data['user_id'],
                'lgu_id' => $data['lgu_id'],
                'incident_code' => $data['incident_code'],
                'created_by' => $data['created_by'],
                'incident_date' => $data['incident_date'],
                'disaster_type' => $data['disaster_type'],
                'start' => $data['start'],
                'end' => $data['end'],
                'longitude' => $data['longitude'],
                'latitude' => $data['latitude'],
                'district_code' => $data['district_code'],
                'municipality_code' => $data['municipality_code'],
                'barangay_code' => $data['barangay_code'],
                'no_families' => $data['no_families'],
                'no_individual' => $data['no_individual'],
                'dead' => $data['dead'],
                'injured' => $data['injured'],
                'missing' => $data['missing'],
                'residential' => $data['residential'],
                'commercial' => $data['commercial'],
                'mix' => $data['mix'],
                'total_damage' => $data['total_damage'],
                'partial_damage' => $data['partial_damage'],
                'situational_overview' => $data['situational_overview'],
                'remarks' => $data['remarks'] ?? '',
                'augmentation' => $data['augmentation'] ?? '',
                'progress_status' => $data['progress_status'],
                'status' => $data['status'],
                'dromic_status' => $data['dromic_status'],
                'lce_id' => $data['lce_id'],
                'file_id' => $fileId->id,
            ]);

            if (isset($data['disaggregated_data'])) {
                $disaggregated_data = json_decode($data['disaggregated_data']);
                $this->insertArchivedDisaggregatedData($disaggregated_data, $info);
            }
            if (isset($data['evacuation'])) {
                $evacuation_data = json_decode($data['evacuation']);
                $this->insertArchivedEvacuationData($evacuation_data, $info);
            }

            DB::commit();


        } catch (Exception $e) {
            DB::rollBack();
            return [false, $e->getMessage()];
        }
    }
}
