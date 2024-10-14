<?php

namespace App\Http\Resources\Customize;

use App\Http\Resources\AdminResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = null;

        if ($request->type === 'qrt' || $request->type === 'public') {
            // Make sure to access the relationship properly
            $user = new UserResource($this->qrt_public);
        } else {
            $user = new AdminCustomResource($this->admin);
        }

        return [
            'id' => $this->id,
            'type' => $this->type,
            'user_id' => $this->user_id,
            'user' => $user,
            'date' => $this->created_at->format('Y-m-d'),
            'time' => $this->created_at->format('H:i:s'),
            'activity' => $this->activity,
        ];
    }

}
