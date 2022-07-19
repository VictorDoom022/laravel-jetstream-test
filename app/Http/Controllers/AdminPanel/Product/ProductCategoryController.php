<?php

namespace App\Http\Controllersc\AdminPanel\Product;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Product;

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

        return [
            'message'=> 'Product category created successfully',
        ];
    }

    public function getProductCategory(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $productCategory = ProductCategory::get();

        return response($productCategory, 200);
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
        
        return [
            'message'=> 'Product category updated',
        ];
    }

    public function deleteProductCategory(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $productCategoryID = $request->productCategoryID;

        $product = Product::where('productCategoryID', $productCategoryID)->get();

        if(count($product) > 0){
            return response(
                [
                    'message'=> 'Cannot delete. Some product is linked to this category',
                ], 201
            );
        }else{
            ProductCategory::where('id', $productCategoryID)->delete();

            return [
                'message'=> 'Product category deleted successfully',
            ];
        }
    }
}
