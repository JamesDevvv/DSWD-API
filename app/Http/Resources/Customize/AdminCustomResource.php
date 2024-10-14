<?php

namespace App\Http\Resources\Customize;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminCustomResource extends JsonResource
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
            'employee_name' => $this->fullname(),
            'office' => $this->office,
            'email' => $this->email,
            'role' => new RoleResouce($this->role),
            'date_created' => $this->created_at,
            'last_login' => new LastloginResource($this->last_login),
        ];
    }
}
