<?php

namespace App\Http\Controllers\AdminPanel\Product;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Product;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;

class ProductCategoryController extends Controller
{
    public function addProductCategory(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $fields = $request->validate([
            'productCategoryName' => ['required', 'string'],
        ]);

        $productCategory = ProductCategory::create([
            'productCategoryName' => $fields['productCategoryName']
        ]);

        session()->flash('flash.message', 'Product category created successfully');
        // session()->flash('flash.bannerStyle', 'success');

        return redirect('/manageProductCategory')->with([
            'data' => 'Product category created successfully'
        ]);
    }

    public function getProductCategory(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $productCategory = ProductCategory::get();

        return Inertia::render(
            'Product/ManageProductCategory', [
                'productCategory' => $productCategory
            ]);
    }

    public function updateProductCategory(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $productCategoryID = $request->productCategoryID;

        $fields = $request->validate([
            'productCategoryName' => ['required', 'string'],
        ]);

        $productCategory = ProductCategory::where('id', $productCategoryID)->first();
        $productCategory->productCategoryName = $fields['productCategoryName'];
        $productCategory->save();
        
        session()->flash('flash.message', 'Product category updated');

        return redirect('/manageProductCategory');
    }

    public function deleteProductCategory(Request $request, $productCategoryID){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $product = Product::where('productCategoryID', $productCategoryID)->get();

        if(count($product) > 0){

            session()->flash('flash.banner', 'Cannot delete. Some product is linked to this category');
            session()->flash('flash.bannerStyle', 'danger');

            return redirect('/manageProductCategory');

        }else{
            ProductCategory::where('id', $productCategoryID)->delete();

            session()->flash('flash.message', 'Product category deleted successfully');

            return redirect('/manageProductCategory');
        }
    }
}
