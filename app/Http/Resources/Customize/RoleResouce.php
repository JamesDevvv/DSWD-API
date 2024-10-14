<?php

namespace App\Http\Resources\Customize;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResouce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
