<?php

namespace App\Http\Resources\Reference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string=> $this->, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'description'=> $this->description,
            'local_goverment_unit'=> $this->local_goverment_unit,
            'emergency_operation_center'=> $this->emergency_operation_center,
            'regional_director'=> $this->regional_director,
            'local_chief_executive'=> $this->local_chief_executive,
            'permissions'=> $this->whenLoaded('permissions'),
        ];
    }
}
