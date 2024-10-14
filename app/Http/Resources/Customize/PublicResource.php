<?php

namespace App\Http\Resources\Customize;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicResource extends JsonResource
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
            'name' => $this->fullname,
            'email' => $this->email,
            'date_created' => $this->created_at,
            'status' => $this->status,
            'last_login' => new LastloginResource($this->last_login),
        ];
    }
}
