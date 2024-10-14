<?php

namespace App\Http\Resources\Reference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RolePermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string=> $this->, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'feature_name' => $this->feature_name,
            'role_id' => $this->role_id,
            'create' => $this->create,
            'view' => $this->view,
            'modify' => $this->modify,
            'delete' => $this->delete,
        ];
    }
}
