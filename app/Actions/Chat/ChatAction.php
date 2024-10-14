<?php

namespace App\Actions\Chat;

use App\Actions\Reference\ReferenceAction;
use App\Events\MessageSent;
use App\Models\AdminModel;
use App\Models\Chats\ChatModel;
use App\Models\Chats\MessageModel;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;

class ChatAction
{
    public function createOrFindChat($request)
    {
        try {
            $validatedData = $request->validate([
                'sender_id' => 'required|integer',
                'receiver_id' => 'required|integer',
                'sender_type' => 'required|string',
                'receiver_type' => 'required|string',
            ]);

            $chat = ChatModel::where([
                ['sender_id', $validatedData['sender_id']],
                ['receiver_id', $validatedData['receiver_id']],
                ['sender_type', $validatedData['sender_type']],
                ['receiver_type', $validatedData['receiver_type']],
            ])->orWhere(function ($query) use ($validatedData) {
                $query->where([
                    ['sender_id', $validatedData['receiver_id']],
                    ['receiver_id', $validatedData['sender_id']],
                    ['sender_type', $validatedData['receiver_type']],
                    ['receiver_type', $validatedData['sender_type']],
                ]);
            })->first();

            if (!$chat) {
                $chat = ChatModel::create([
                    'sender_type' => $request->sender_type,
                    'sender_id' => $request->sender_id,
                    'receiver_type' => $request->receiver_type,
                    'receiver_id' => $request->receiver_id,
                ]);
            }

            return response()->json(['status' => 'success', 'message' => 'Chat created successfully', 'chat_id' => $chat->id], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function sendMessage($request)
    {
        try {
            $validatedData = $request->validate([
                'chat_id' => 'required|string',
                'sender_type' => 'required|string',
                'sender_id' => 'required|string',
                'content' => 'required|string',
            ]);

            $chat = ChatModel::where('id', $validatedData['chat_id'])->first(); // Retrieve the first chat instance

            if (!$chat) {
                return response()->json(['error' => 'Chat not found'], 404);
            }


            DB::beginTransaction();

            $data = MessageModel::create([
                'chat_id' => $chat->id,
                'sender_type' => $validatedData['sender_type'],
                'sender_id' => $validatedData['sender_id'],
                'content' => $validatedData['content']
            ]);

            $message = $this->HandleEvent($data);

            $chatData = ChatModel::where('id', $chat->id)->first();
            $chatData->update(['is_seen' => null]);


            $notifcation = new ReferenceAction();

            $details = [

                'type' => 'message-notification',
                'sender_type' => auth('sanctum')->user()->type ?? 'admin',
                'receiver_type' => $chatData->receiver_type,
                'recieveId' => $chatData->receiver_id,
                'message_id' => $data->chat_id,
                'content' => $data->content

            ];
            $notifcation->pushNotification($details);

            DB::commit();


            return response()->json(['status' => true, 'message' => $message], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
        }
    }


    // Function to list chats for a specific user
    public function listUserChats($request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|string',
                'user_type' => 'required|string',
                'filter' => 'required|string|in:all,admin,qrt',
            ]);

            $userId = $validatedData['user_id'];
            $filter = $validatedData['filter'];
            $query = ChatModel::query();

            // Retrieve chats involving the user
            $query->where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId);
            });

            // Apply filter
            if ($filter === 'admin') {
                $query->where(function ($q) {
                    $q->where('sender_type', 'admin')
                        ->where('receiver_type', 'admin');
                });
            } elseif ($filter === 'qrt') {
                $query->where(function ($q) {
                    $q->where('sender_type', 'qrt')
                        ->orWhere('receiver_type', 'qrt');
                });
            }

            $chats = $query->get();

            foreach ($chats as $chat) {
                // Check sender type
                if ($chat->sender_type === 'admin') {
                    $chat->load('admin_sender');
                } elseif ($chat->sender_type === 'qrt') {
                    $chat->load('qrt_sender');
                }

                // Check receiver type
                if ($chat->receiver_type === 'admin') {
                    $chat->load('admin_reciever');
                } elseif ($chat->receiver_type === 'qrt') {
                    $chat->load('qrt_reciever');
                }

                // Load the latest message for each chat
                $latestMessage = $chat->messages()->orderBy('created_at', 'desc')->first();
                $chat->messages = $latestMessage;

                // Store the updated_at of the latest message for sorting
                $chat->latest_message_updated_at = $latestMessage ? $latestMessage->updated_at : $chat->updated_at;
            }

            // Sort chats by latest message's updated_at in descending order
            $sortedChats = $chats->sortByDesc('latest_message_updated_at');

            return response()->json(['status' => 'success', 'data' => $sortedChats->values()->all()], 200);
            
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function conversation($id)
    {
        try {
            $query = MessageModel::query();

            $message = $query->where('chat_id', $id)
                ->latest()
                ->paginate(10);

            foreach ($message as $msg) {
                // Check sender type
                if ($msg->sender_type === 'admin') {
                    $msg->load('admin_sender');
                } elseif ($msg->sender_type === 'qrt') {
                    $msg->load('qrt_sender');
                }

                // Check receiver type
                if ($msg->receiver_type === 'admin') {
                    $msg->load('admin_reciever');
                } elseif ($msg->receiver_type === 'qrt') {
                    $msg->load('qrt_reciever');
                }
            }

            return response()->json(['status' => 'success', 'data' => $message], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function allUserList($request)
    {
        try {
            switch ($request->type) {
                case 'qrt':
                    // return all admins and users except for LCEs
                    $admin = AdminModel::whereHas('role', function ($query) {
                        $query->where('local_chief_executive', "!=", 1)->where('regional_director', "!=", 1);
                    })->orderBy('firstname')->get(['id', 'firstname', 'lastname', 'email'])->map(function ($admin) {
                        $admin->fullname = $admin->firstname . ' ' . $admin->lastname;
                        return $admin;
                    });

                    $qrt = User::where('type', 'qrt')->orderBy('fullname')->get(['id', 'fullname', 'email']);

                    return response()->json([
                        'status' => true,
                        'message' => 'fetch data successfully',
                        'admin' => $admin,
                        'qrt' => $qrt,
                    ], 200);

                case 'admin':
                    $userAdmin = auth('sanctum')->user();

                    // check if the user is lce based on the role_id on roles table
                    $isLce = $userAdmin->role->local_chief_executive == 1;

                    // check if the user is lgu based on the role_id on roles table
                    $isLgu = $userAdmin->role->local_goverment_unit == 1;

                    // check if the user is rd based on the role_id on roles table
                    $isRd = $userAdmin->role->regional_director == 1;

                    if ($isLce) {
                        $admin = AdminModel::where('role_id', $userAdmin->sub_role_id)->orderBy('firstname')->get(['id', 'firstname', 'lastname', 'email'])->map(function ($admin) {
                            $admin->fullname = $admin->firstname . ' ' . $admin->lastname;
                            return $admin;
                        });

                        $qrt = [];

                        return response()->json([
                            'status' => true,
                            'message' => 'fetch data successfully',
                            'admin' => $admin,
                            'qrt' => $qrt,
                        ], 200);
                    }

                    if($isRd){
                        // return all admins that has local_chief_executive = 1
                        $lceAdmin = AdminModel::whereHas('role', function ($query) {
                            $query->where('local_chief_executive', 1);
                        })->orderBy('firstname')->get(['id', 'firstname', 'lastname', 'email'])->map(function ($admin) {
                            $admin->fullname = $admin->firstname . ' ' . $admin->lastname;
                            return $admin;
                        });

                        // return all admins that has emergency_operation_center = 1
                        $eocAdmin = AdminModel::whereHas('role', function ($query) {
                            $query->where('emergency_operation_center', 1);
                        })->orderBy('firstname')->get(['id', 'firstname', 'lastname', 'email'])->map(function ($admin) {
                            $admin->fullname = $admin->firstname . ' ' . $admin->lastname;
                            return $admin;
                        });

                        $admin = $lceAdmin->merge($eocAdmin);

                        $qrt = User::where('type', 'qrt')->orderBy('fullname')->get(['id', 'fullname', 'email']);
 
                        return response()->json([
                            'status' => true,
                            'message' => 'fetch data successfully',
                            'admin' => $admin,
                            'qrt' => $qrt,
                        ], 200);

                    }

                    if ($isLgu) {
                        // return Admins that has the same role_id as the user
                        $mainAdmin = AdminModel::where('role_id', $userAdmin->role_id)->get(['id', 'firstname', 'lastname', 'email'])->map(function ($admin) {
                            $admin->fullname = $admin->firstname . ' ' . $admin->lastname;
                            return $admin;
                        });


                        // return admin that has the same role_id as the user based on sub_role_id
                        $subAdmin = AdminModel::where('sub_role_id', $userAdmin->role_id)->get(['id', 'firstname', 'lastname', 'email'])->map(function ($admin) {
                            $admin->fullname = $admin->firstname . ' ' . $admin->lastname;
                            return $admin;
                        });

                        $admin = $mainAdmin->merge($subAdmin);
                        $qrt = [];

                        return response()->json([
                            'status' => true,
                            'message' => 'fetch data successfully',
                            'admin' => $admin,
                            'qrt' => $qrt,
                        ], 200);
                    }

                    

                default:
                    $admin = AdminModel::orderBy('firstname')->get(['id', 'firstname', 'lastname', 'email'])->map(function ($admin) {
                        $admin->fullname = $admin->firstname . ' ' . $admin->lastname;
                        return $admin;
                    });
                    $qrt = User::where('type', 'qrt')->orderBy('fullname')->get(['id', 'fullname', 'email']);

                    return response()->json([
                        'status' => true,
                        'message' => 'fetch data successfully',
                        'admin' => $admin,
                        'qrt' => $qrt,
                    ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function HandleEvent($data)
    {
        $query = MessageModel::query();

        // Check sender type
        if ($data->sender_type === 'admin') {
            $query->with('admin_sender');
        } elseif ($data->sender_type === 'qrt') {
            $query->with('qrt_sender');
        }

        // Check receiver type
        if ($data->receiver_type === 'admin') {
            $query->with('admin_reciever');
        } elseif ($data->receiver_type === 'qrt') {
            $query->with('qrt_reciever');
        }

        $message = $query->where('chat_id', $data->chat_id)
            ->latest()
            ->paginate(10);

        // Broadcasting the event
        event(new MessageSent($message));

        return $message;
    }


    public function is_seen($id)
    {
        try {

            $query = ChatModel::where('id', $id)->first();

            if (!$query) {
                return response()->json([
                    'status' => false,
                    'message' => 'data not found'
                ], 404);
            }

            $query->update([
                'is_seen' => 'true'
            ]);

            return response()->json([
                'status' => true,
                'message' => 'success'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }
}

