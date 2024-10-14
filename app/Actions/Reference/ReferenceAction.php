<?php

namespace App\Actions\Reference;

use App\Http\Resources\Reference\BarangayResource;
use App\Http\Resources\Reference\MunicipalityResource;
use App\Http\Resources\Reference\ProvinceResource;
use App\Models\AdminModel;
use App\Models\Reference\BarangayModel;
use App\Models\Reference\DisasterModel;
use App\Models\Reference\MunicipalityModel;
use App\Models\Reference\NotificationModel;
use App\Models\Reference\ProvinceModel;
use App\Models\Reference\RoleModel;
use App\Services\EmailNotification;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Pusher\PushNotifications\PushNotifications;

class ReferenceAction
{
    public function MunicipalityCity(Request $request)
    {
        try {
            $data = ProvinceModel::orderBy('name', 'asc')->get();

            $data = new ProvinceResource($data);
            return [true, 'success', $data];
        } catch (\Exception $e) {
            return [false, $e->getMessage()];
        }
    }
    public function CityDistrict($code)
    {
        try {
            $list = MunicipalityModel::where('province_code', $code)
                ->orderBy('name', 'asc')
                ->get();
            $data = new MunicipalityResource($list);
            return [true, 'success', $data];
        } catch (\Exception $e) {
            return [false, $e->getMessage()];
        }
    }
    public function Barangay($code)
    {
        try {
            $list = BarangayModel::where('municipality_code', $code)
                ->orderBy('name', 'asc')
                ->get();
            $data = new BarangayResource($list);
            return [true, 'success', $data];
        } catch (\Exception $e) {
            return [false, $e->getMessage()];
        }
    }
    public function DisasterType(Request $request)
    {
        try {
            $data = DisasterModel::get();
            return [true, 'success', $data];
        } catch (\Exception $e) {
            return [false, $e->getMessage()];
        }
    }
    public function getLGU(Request $request)
    {
        try {
            $data = RoleModel::where('local_goverment_unit', 1)
                ->where('name', 'like', 'LGU - %')
                ->latest()
                ->get();

            return [true, 'success', $data];
        } catch (\Exception $e) {
            return [false, $e->getMessage()];
        }
    }


    public function pushNotification($details)
    {
        try {


            $notification = NotificationModel::create([
                'type' => $details['type'],
                'sender_type' => $details['sender_type'],
                'from_id' => auth('sanctum')->user()->id,
                'receiver_type' => $details['receiver_type'] ?? null,
                'recieve_id' => $details['recieveId'] ?? null,
                'group_recieve_id' => $details['groupRecieveId'] ?? null,
                'message_id' => $details['message_id'] ?? null,
                'content' => $details['content'],
            ]);


            $this->PusherBeams($notification);


        } catch (\Exception $e) {
            \Log::error('Error occurred while pushing notification: ' . $e->getMessage());
            return [false, $e->getMessage()];
        }

    }
    public function NoficationList()
    {
        //di ko pa alam pano
    }

    public function sendSms($data)
    {
        // for sms
        // $SmsService = new SmsService();
        $emailNotification = new EmailNotification();

        $content = 'Dear <strong>' . strtoupper($data['fullname']) . '</strong>,<br>This is a notification from the DSWD project Resolve. Please log in to the QRT project resolve app and notify the <strong>' . strtoupper($data['lgu_name']) . '</strong> if you are available.<br>Thank you.';

        //$to = '+63' . $data['number'];
        //for sms
        // $response = $SmsService->sendSms($to, $content);
        $emailNotification->EmailNotification($data['email'], $content);

        return response()->json([
            'message' => 'SMS sent successfully!'
        ]);
    }


    public function PusherBeams($Notifcation)
    {
        $notification = $Notifcation;



        if ($notification->receiver_type === 'admin') {
            $userData = AdminModel::with('role')->where('id', $notification->receiver_id)->get();

            if ($userData->role->emergency_operation_center === '1' || $userData->role->local_goverment_unit === '1') {
                $beamClient = new PushNotifications([
                    'instanceId' => env('ADMIN_BEAMS_INSTANCE_ID'),
                    'secretKey' => env('ADMIN_BEAMS_SECRET_KEY'),
                ]);
            }
            if ($userData->role->regional_director === '1' || $userData->role->local_chief_executive === '1') {
                $beamClient = new PushNotifications([
                    'instanceId' => env('LEAD_BEAMS_INSTANCE_ID'),
                    'secretKey' => env('LEAD_BEAMS_SECRET_KEY'),
                ]);
            }


        }
        if ($notification->receiver_type === 'qrt') {

            $beamClient = new PushNotifications([
                'instanceId' => env('QRT_BEAMS_INSTANCE_ID'),
                'secretKey' => env('QRT_BEAMS_SECRET_KEY'),
            ]);

        }

        if ($notification->receiver_type === 'public') {

            $beamClient = new PushNotifications([
                'instanceId' => env('PUBLIC_BEAMS_INSTANCE_ID'),
                'secretKey' => env('PUBLIC_BEAMS_SECRET_KEY'),
            ]);

        }


        if ($notification->type === 'update-status') {
            $beamClient->publishToInterests(
                ['eoc'],
                [
                    'fcm' => [
                        'notification' => [
                            'title' => $this->kebabToCamel($notification->type),
                            'body' => $notification->content,
                        ],
                    ],
                    'apns' => [
                        'aps' => [
                            'alert' => [
                                'title' => $this->kebabToCamel($notification->type),
                                'body' => $notification->content,
                            ],
                        ],
                    ],
                    'web' => [
                        'notification' => [
                            'title' => $this->kebabToCamel($notification->type),
                            'body' => $notification->content,
                        ],
                    ],

                ]
            );
        }

        if ($notification->type === 'for-validation' || $notification->type === 'augmentation-notif') {
            $beamClient->publishToInterests(
                ['lgu' . '_' . $notification->group_recieve_id],
                [
                    'fcm' => [
                        'notification' => [
                            'title' => $this->kebabToCamel($notification->type),
                            'body' => $notification->content,
                        ],
                    ],
                    'apns' => [
                        'aps' => [
                            'alert' => [
                                'title' => $this->kebabToCamel($notification->type),
                                'body' => $notification->content,
                            ],
                        ],
                    ],
                    'web' => [
                        'notification' => [
                            'title' => $this->kebabToCamel($notification->type),
                            'body' => $notification->content,
                        ],
                    ],
                ]
            );
        }


        if ($notification->type === 'message-notification' || $notification->type === 'approval-notif') {
            $beamClient->publishToInterests(
                [$notification->receiver_type . '_' . $notification->recieve_id],
                [
                    'fcm' => [
                        'notification' => [
                            'title' => $this->kebabToCamel($notification->type),
                            'body' => $notification->content,
                        ],
                    ],
                    'apns' => [
                        'aps' => [
                            'alert' => [
                                'title' => $this->kebabToCamel($notification->type),
                                'body' => $notification->content,
                            ],
                        ],
                    ],
                    'web' => [
                        'notification' => [
                            'title' => $this->kebabToCamel($notification->type),
                            'body' => $notification->content,
                        ],
                    ],

                ]
            );
        }

    }

    // function that will convert snake-case to Camel Case
    public function kebabToCamel(string $kebab): string
    {
        return ucwords(str_replace('-', ' ', $kebab));
    }

    public function lceList(Request $request)
    {
        try {
            $data = RoleModel::where('local_chief_executive', 1)->latest()->get();
            return [true, 'success', $data];
        } catch (\Exception $e) {
            return [false, $e->getMessage()];
        }
    }
}
