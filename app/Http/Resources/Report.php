<?php

namespace App\Http\Resources;

use App\Http\Resources\User as UserResource;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class Report extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'description' => $this->resource->description,
            'car_plates' => $this->resource->car_plates,
            'road_name' => $this->resource->road_name,
            'posted_at' => $this->resource->posted_at,
            'occurred_at' => $this->resource->occurred_at,
            'latitude' => $this->resource->latitude,
            'longitude' => $this->resource->longitude,
            'user' => (new UserResource(User::find($this->resource->user_id)))
        ];
    }
}
