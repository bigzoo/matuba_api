<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'phone_no' => $this->resource->phone_no,
            'email' => $this->resource->email,
            'nickname' => $this->resource->nickname,
            'county' => $this->resource->county
        ];
    }
}
