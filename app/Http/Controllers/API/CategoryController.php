<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Str;


class CategoryController extends Controller
{
    public function index(){
        $data['categories'] = Category::get();

        return response()->json(['success' => true, 'data' => $data],200);
    }

    public function store(Request $request){

        $validator = \Validator::make($request->all(), [
            'name' => 'required|max:255|unique:categories,name',
            'desc' => 'max:255',

        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'msgs' => $validator->errors()],422);
        }

        $store = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'desc' => $request->desc,
        ]);

        if(!empty($store->id)){
            return response()->json(['success' => true, 'data' => $store, 'msg' => 'Category Added'],200);
        }
        return response()->json(['success' => false, 'msg' => 'Something Went Wrong'],422);
    }

    public function update(Request $request){
        $validator = \Validator::make($request->all(), [
            'name' => 'required|max:255',
            'desc' => 'max:255',

        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'msgs' => $validator->errors()],422);
        }

        $category = Category::where('id',$request->id)->first(['id','name','slug','desc']);
        $update = $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'desc' => $request->desc,
        ]);

        if(!empty($update > 0)){
            return response()->json(['success' => true, 'data' => $category, 'msg' => 'Category Updated'],200);
        }
        return response()->json(['success' => false, 'msg' => 'Something Went Wrong'],422);
    }
    public function delete(Request $request){
        $category = Category::where('id',$request->id)->firstorfail();
        if(!empty($category)){
            $category->delete();
        return response()->json(['success' => true, 'msg' => 'Category Deleted'],200);
        }
        return response()->json(['success' => false, 'msg' => 'Something Went Wrong'],422);
    }


}
