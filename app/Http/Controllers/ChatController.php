<?php

namespace App\Http\Controllers;

use App\Actions\Chat\ChatAction;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    //
    protected $ChatAction;

    public function __construct(ChatAction $ChatAction)
    {
        $this->ChatAction = $ChatAction;
    }

    public function createOrFindChat(Request $request)
    {
        $data = $this->ChatAction->createOrFindChat($request);

        return $data;
    }
    public function sendMessage(Request $request)
    {
        $data = $this->ChatAction->sendMessage($request);

        return $data;
    }
    public function listUserChats(Request $request)
    {
        $data = $this->ChatAction->listUserChats($request);

        return $data;
    }
    public function allUserList(Request $request){

        $data = $this->ChatAction->allUserList($request);

        return $data;
    }
    public function conversation($id)
    {
        $data = $this->ChatAction->conversation($id);

        return $data;
    }

    public function is_seen($id)
    {
        $data = $this->ChatAction->is_seen($id);

        return $data;
    }
}
