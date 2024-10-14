<?php

namespace App\Http\Resources;

use App\Http\Resources\Reference\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string=> $this->, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'role_id' => $this->role_id,
            'sub_role_id' => $this->sub_role_id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'age' => $this->age,
            'birthdate' => $this->birthdate,
            'contact' => $this->contact,
            'address' => $this->address,
            'office' => $this->office,
            'division' => $this->division,
            'service' => $this->service,
            'group' => $this->group,
            'email' => $this->email,
            'role' => $this->whenLoaded('role'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];

    }
}
