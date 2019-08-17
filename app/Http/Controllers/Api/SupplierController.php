<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Supplier;
use App\Purchase;
use App\Http\Resources\SupplierCollection;
use App\Http\Resources\SupplierResource;
class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supplier = new SupplierCollection(Supplier::orderBy('id','DESC')->paginate(10));
        return $supplier;
    }
    public function allsupplier(){
        $all_supplier = Supplier::latest()->get();
        return new SupplierCollection($all_supplier);
    }
    public function search($f,$q){
        return new SupplierCollection(Supplier::where($f,'LIKE',"%$q%")->latest()->paginate(10));
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
            'name' => 'required',
            'company_name' => 'required',
            'email' => 'required|email|unique:suppliers',
            'phone' => 'required|numeric',
            'address' => 'required',
        ]);

        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->company_name = $request->company_name;
        $supplier->email = $request->email;
        $supplier->phone = $request->phone;
        $supplier->address = $request->address;
        $supplier->save();
        return new SupplierResource($supplier);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $supplier = new SupplierResource(Supplier::findOrFail($id));
        return $supplier;
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
            'name' => 'required',
            'company_name' => 'required',
            'email' => 'required|email|unique:suppliers,email,'.$id,
            'phone' => 'required|numeric',
            'address' => 'required',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->name = $request->name;
        $supplier->company_name = $request->company_name;
        $supplier->email = $request->email;
        $supplier->phone = $request->phone;
        $supplier->address = $request->address;
        $supplier->save();
        return new SupplierResource($supplier);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delPur = Purchase::where('supplier_id',$id)->delete();
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return new SupplierResource($supplier);
    }
}
