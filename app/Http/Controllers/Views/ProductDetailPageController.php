<?php

namespace App\Http\Controllers\Views;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Product;

class ProductDetailPageController extends Controller
{
    public function index($id)
    {
        $product = Product::where('id', $id)->first();

        if ($product == null) {
            abort(404);
        }

        $prevProductID = Product::where('id', '<', $product->id)->max('id');
        $nextProductID = Product::where('id', '>', $product->id)->min('id');

        $relatedProduct = Product::where('productCategoryID', $product->productCategoryID)
            ->where('id', '!=', $id)
            ->take(4)->get();

        return view('productDetail', [
            'title' => $product->productName . " - Products - IDO'S Clinic",
            "description" => $product->productShortDesc,
            'prevProductID' => $prevProductID,
            'nextProductID' => $nextProductID,
            'product' => $product,
            'relatedProducts' => $relatedProduct,
        ]);
    }
}
