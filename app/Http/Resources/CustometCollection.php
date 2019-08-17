<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CustometCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function($customer){
                return [
                    'id' => $customer->id,
                    'name' => $customer->c_name,
                    'company_name' => $customer->company_name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'address' => $customer->address,
                    'created_at' =>$customer->created_at
                ];
            })
        ];
    }
}
