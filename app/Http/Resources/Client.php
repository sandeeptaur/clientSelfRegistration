<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;

class Client extends JsonResource
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
            'id' => $this->id,
            'name' => $this->client_name,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'zipCode' => $this->zip,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'phoneNo1' => $this->phone_no1,
            'phoneNo2' => $this->phone_no2,
            'totalUser' => $this->whenLoaded('user')?[
                "all"=> $this->user->count(),
                "active"=> $this->user->where('status','Active')->count(),
                "inactive"=> $this->user->where('status','Inactive')->count()]:null,
            'startValidity' => $this->start_validity,
            'endValidity' => $this->end_validity,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
