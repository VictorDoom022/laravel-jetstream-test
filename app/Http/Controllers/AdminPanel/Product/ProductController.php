<?php

namespace App\Http\Controllers\AdminPanel\Product;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Service;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function addProduct(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $productCategoryID = $request->productCategoryID;
        $productFullDetails = $request->productFullDetails;

        $fields = $request->validate([
            'productName' => ['required', 'string'],
            'productShortDesc' => ['required', 'string'],
        ]);

        $product = Product::create([
            'productName' => $fields['productName'],
            'productCategoryID' => $productCategoryID,
            'productShortDesc' => $fields['productShortDesc'],
            'productFullDetails' => $productFullDetails,
        ]);

        if($request->hasfile('productImageSrc')){
            $file = $request->file('productImageSrc');

            //save the file into /public/upload/productImage/{id}/
            $productImage = $file->getClientOriginalName();
            $filePath = 'upload/productImage/' . $product->id . '/';
            $fullFilePath = $file->move(public_path($filePath), $productImage);

            $product->productImageSrc = $filePath . $productImage;
            $product->save();
        }

        session()->flash('flash.message', 'Product added successfully');

        return redirect('/manageProduct');
    }

    public function getProducts(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $product = Product::leftJoin("product_categories", "product_categories.id", "=", "products.productCategoryID")->get([
            'products.id',
            'products.productName',
            'products.updated_at',
            'product_categories.productCategoryName'
        ]);

        return Inertia::render(
            'Product/ManageProduct', [
                'product' => $product,
            ]);
    }

    public function getAddProductPageData(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $productCategory = ProductCategory::get();

        return Inertia::render(
            'Product/AddProduct', [
                'productCategory' => $productCategory
            ]);
    }

    public function deleteProduct(Request $request, $productID){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $product = Product::where('id', $productID)->first();
        if($product->productImageSrc != null){
            $productFilePath = public_path() . "/" . $product->productImageSrc;
            File::delete($productFilePath);
        }

        $product->delete();

        session()->flash('flash.message', 'Product deleted successfully');

        return redirect('/manageProduct');
    }

    public function getEditProductPageData(Request $request, $productID){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $product = Product::where('products.id', $productID)
            ->leftJoin("product_categories", "product_categories.id", "=", "products.productCategoryID")
            ->first([
                'products.*',
                'product_categories.productCategoryName'
            ]);

        $productCategory = ProductCategory::get();
        
        return Inertia::render(
            'Product/EditProduct', [
                "product" => $product,
                "productCategory" => $productCategory,
            ]);
    }

    public function editProduct(Request $request, $productID){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $productCategoryID = $request->productCategoryID;
        $productFullDetails = $request->productFullDetails;

        $fields = $request->validate([
            'productName' => ['required', 'string'],
            'productShortDesc' => ['required', 'string'],
        ]);

        $product = Product::where('id', $productID)->first();
        $product->productName = $fields['productName'];
        $product->productCategoryID = $productCategoryID;
        $product->productShortDesc = $fields['productShortDesc'];
        $product->productFullDetails = $productFullDetails;

        if($request->hasfile('productImageSrc')){
            // delete the previous file if user upload new image
            $file = $request->file('productImageSrc');
            $prevProductImageSrc = public_path() . "/" . 'upload/productImage/' . $product->id;
            File::delete($prevProductImageSrc);

            //save the file into /public/upload/productImage/{id}/
            $productImage = $file->getClientOriginalName();
            $filePath = 'upload/productImage/' . $product->id . '/';
            $fullFilePath = $file->move(public_path($filePath), $productImage);

            $product->productImageSrc = $filePath . $productImage;
            $product->save();
        }

        if($request->productImageSrc == null){
            // delete the previous file if user upload new image
            $file = $request->file('productImageSrc');
            $prevProductImageSrc = public_path() . "/" . $product->productImageSrc;
            File::delete($prevProductImageSrc);

            $product->productImageSrc = null;
        }
        $product->save();

        session()->flash('flash.message', 'Product edited successfully');

        return redirect('/manageProduct');
    }
}
