<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaxiQeueeResource extends JsonResource
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
            'taxi_number' => $this->taxi_number,
            'enter_time' => $this->enter_time,
            'exit_time' => $this->exit_time,
            'from' => $this->from,
            'to' => $this->to,
            'passengers' => $this->passengers,
            'status' => $this->status,
            'user' => $this->user,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
