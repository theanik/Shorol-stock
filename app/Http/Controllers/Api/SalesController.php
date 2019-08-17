<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Sales;
use App\Purchase;
use App\Http\Resources\SalesCollection;
use App\Http\Resources\SalesResource;
class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $sale = Sales::orderBy('id','DESC')->paginate(10);
        return new SalesCollection($sale);
    }
    public function salesdata()
    {
        return $sales = DB::table('sales')
                    ->join('purchases','purchases.id','sales.purchases_id')
                    ->join('products','products.id','purchases.product_id')
                    ->join('customers','customers.id','sales.customer_id')
                    ->select('sales.*',
                        'products.name',
                        'products.code',
                        'customers.c_name',
                        'customers.email',
                        'customers.phone',
                        'purchases.retail_price',
                        'purchases.sale_price',
                        'purchases.product_id',
                        'sales.t_price',
                        'sales.quantity'
                    )
                    ->orderBy('sales.id','DESC')
                    ->paginate(10);

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
            'purchases_id' => 'required',
            'customer_id' => 'required',
            'quantity' => 'required'
        ]);

        $newQuantity = $request->quantity;
        $purchaseDetails = Purchase::findOrFail($request->purchases_id);
        if($purchaseDetails){
            if($purchaseDetails->product_quantity > 0 && $purchaseDetails->product_quantity > $newQuantity){
                $singlePrice = $purchaseDetails->sale_price;
                $totalPrice = $singlePrice * $newQuantity;
                $oldQunatity = $purchaseDetails->product_quantity;
                $upQunatity = $oldQunatity - $newQuantity;

                $sale = new Sales();
                $sale->purchases_id = $request->purchases_id;
                $sale->customer_id = $request->customer_id;
                $sale->quantity = $request->quantity;
                $sale->t_price = $totalPrice;
                $sale->save();

                $purchaseDetails->product_quantity = $upQunatity;
                $purchaseDetails->save();

                return new SalesResource($sale);
            }else{
                return Session::flash('message', 'Product is out of stock!'); 
            }
            

        }
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sale = Sales::findOrFail($id);
        $sale->delete();
        return new SalesResource($sale);
    }
}
