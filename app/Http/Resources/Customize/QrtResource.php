<?php

namespace App\Http\Resources\Customize;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QrtResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_name' => $this->fullname,
            'office'=> $this->office,
            'team' => $this->team,
            'email' => $this->email,
            'status' => $this->status,
            'date_created' => $this->created_at->format('Y-m-d H:i:s'),
            'last_login' => new LastloginResource($this->last_login),
        ];
    }
}
