<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    
    public function toArray($request)
    {
        return parent::toArray($request) + [
            'customer' => $this->resource->customer->toArray()
        ];
    }

    // public static function getActivities($request)
    // {
    //     return parent::toArray($request);
    //     // return parent::toArray($request) + [
    //     //     'activity' => $this->resource->activity->toArray()
    //     // ];
    // }
}