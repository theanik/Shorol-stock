<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\Category;
use App\Purchase;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Auth;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $porduct = Product::orderBy('id','DESC')->paginate(10);
        return new ProductCollection($porduct);
    }
    public function allproduct(){
        $all_product = Product::latest()->get();
        return new ProductCollection($all_product);
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
            'cat_name' => 'required',
            'description' => 'required'
        ]);
        
        $product = new Product();
        $product->name = $request->name;
        $product->cat_name = $request->cat_name;
        $product->code = uniqid();
        $product->description = $request->description;
        $product->save();
        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $productResource = new ProductResource(Product::findOrFail($id));
        return $productResource;
    }

    public function search($f,$q){
        return new ProductCollection(Product::where($f,'LIKE',"%$q%")->latest()->paginate(10));
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
            'cat_name' => 'required',
            'description' => 'required'
        ]);

        $product = Product::findOrFail($id);
        //return $product->code;
        $product->name = $request->name;
        $product->cat_name = $request->cat_name;
        $product->code = $product->code;
        $product->description = $request->description;
        $product->save();
        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delPur = Purchase::where('product_id',$id)->delete();
        $product = Product::findOrFail($id);
        $product->delete();
        return new ProductResource($product);
    }
}
