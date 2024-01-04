<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{


    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {


        return [
//            "success" => true,
//            "page" => 1,
//            "total_pages" => 10,
//            "total_users" => 47,
//            "count" => $this->collection->count(),
//            'links' => [
//                "next_url" => "https://frontend-test-assignment-api.abz.agency/api/v1/users?page=2&count=5",
//                "prev_url" => null
//            ],
            'users' => $this->collection,

        ];
    }
}
