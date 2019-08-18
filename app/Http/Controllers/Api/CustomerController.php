<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customer;
use App\Sales;
use App\Http\Resources\CustometCollection;
use App\Http\Resources\CustometResource;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return Customer::all();
        $customerCollection = new CustometCollection(Customer::orderBy('id','DESC')->paginate(10));
        return $customerCollection;
    }
    public function allcustomer(){
        $all_customer = Customer::latest()->get();
        return new CustometCollection($all_customer);
    }
    public function search($f,$q){
        return new CustometCollection(Customer::where($f,'LIKE',"%$q%")->latest()->paginate(10));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'c_name' => 'required',
            'company_name' => 'required',
            'email' => 'required|email|unique:customers',
            'phone' => 'required|numeric',
            'address' => 'required',
        ]);

        $customer = new Customer();
        $customer->c_name = $request->c_name;
        $customer->company_name = $request->company_name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->save();
        return new CustometResource($customer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customerResource = new CustometResource(Customer::findOrFail($id));
        return $customerResource;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         $request->validate([
            'c_name' => 'required',
            'company_name' => 'required',
            'email' => 'required|email|unique:customers,email,'.$id,
            'phone' => 'required|numeric',
            'address' => 'required',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->c_name = $request->c_name;
        $customer->company_name = $request->company_name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->save();
        return new CustometResource($customer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delSales = Sales::where('customer_id',$id)->delete();
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return new CustometResource($customer);
    }
}
