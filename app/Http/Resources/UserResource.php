<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id_number' => $this->id_number,
            'type' => $this->type,
            'team'=> $this->team,
            'fullname'=> $this->fullname,
            'age'=> $this->age,
            'address'=> $this->address,
            'contact'=> $this->contact,
            'province_code'=> $this->province_code,
            'municipality_code'=> $this->municipality_code,
            'barangay_code'=> $this->barangay_code,
            'postal_code'=> $this->postal_code,
            'gender'=> $this->gender,
            'email'=> $this->email,
            'provider'=>$this->provider,
            'provider_id'=>$this->provider,
            'status'=> $this->status,
            'approver_id'=> $this->approver_id,
            'avatar' => $this->whenLoaded('avatar'),
            'training_files' => $this->whenLoaded('training_files')
        ];
    }
}
