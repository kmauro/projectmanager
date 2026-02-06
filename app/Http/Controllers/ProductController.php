<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    

    public function getSubcategories($categoryId)
    {
        $category = Category::find($categoryId);
        if(!$category){
            return response()->json([], 404);
        }
        $subcategories = Category::find($categoryId)->subcategories;
        return response()->json($subcategories);
    }

    public static function updateProduct($data, $productId){
        $product = Product::find($productId);
        if($product){
            $product->update($data);
            return true;    
        }else{
            return false;
        }
        
    }

    public static function addProduct($data){
        Product::create($data);
    }

    public static function deleteProduct($productId){
        $product = Product::find($productId);
        if($product){
            $product->delete();
            return true;
        }else{
            return false;
        }
    }

    public static function getProductSuppliers($productId){
        $product = Product::find($productId);
        if (!$product) {
            abort(404);
        }
        return $product->suppliers()->paginate(10);
    }
}
