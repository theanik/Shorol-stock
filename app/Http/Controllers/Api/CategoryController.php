<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryCollection;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_category = Category::orderBy('id','DESC')->paginate(10);
        return new CategoryCollection($all_category);
    }
    public function allcategory(){
        $all_category = Category::latest()->get();
        return new CategoryCollection($all_category);
    }
    
    public function search($query){
        $search_data = Category::where('name','LIKE',"%$query%")->orWhere('id','LIKE',"%$query%")->latest()->paginate(10);
        return new CategoryCollection($search_data);
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
            'name' => 'required'
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->save();
        return new CategoryResource($category);
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
        $request->validate([
            'name' => 'required'
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->save();
        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return new CategoryResource($category);
    }
}
