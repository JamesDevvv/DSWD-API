<?php

namespace App\Http\Resources\EOCLGU;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AsOfResource extends JsonResource
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
            'incident_code' => $this->incident_code,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
