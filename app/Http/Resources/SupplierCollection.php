<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SupplierCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function($supplier){
                return [
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                    'company_name' => $supplier->company_name,
                    'email' => $supplier->email,
                    'phone' => $supplier->phone,
                    'address' => $supplier->address,
                    'created_at' =>$supplier->created_at
                ];
            })
        ];
    }
}
