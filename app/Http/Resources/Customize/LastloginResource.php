<?php

namespace App\Http\Resources\Customize;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LastloginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'last_login' => $this->last_used_at
        ];
    }
}
