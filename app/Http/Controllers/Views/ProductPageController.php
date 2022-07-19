<?php

namespace App\Http\Controllers\Views;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Product;

class ProductPageController extends Controller
{
    public function index($id)
    {
        $productCategory = ProductCategory::where('id', $id)->first();

        if ($productCategory == null) {
            abort(404);
        }

        $products = Product::where('productCategoryID', $id)->get();

        return view('products', [
            'title' => $productCategory->productCategoryName . " - Products - IDO'S Clinic",
            "description" => "Welcome to IDO's Clinic, a place where science, medicine and arts meet. State of the art lasers and aesthetics innovations to restore and enhance your skin, face and body.",
            'productCategory' => $productCategory,
            'products' => $products,
        ]);
    }
}
