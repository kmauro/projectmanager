<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class StockController extends Controller
{
    public static function getStock()
    {
        return Product::with('subcategory.category')->paginate(15);
    }

    public static function getProduct($id)
    {
        return Product::find($id);
    }
    
    public static function getQuantities()
    {
        return Product::select('id', 'name', 'stock', 'min_stock')->get();
    }

    public static function updateQuantities($data)
    {
        foreach ($data['productid'] as $index => $productId) {
            $product = Product::find($productId);
            if ($product) {
                $newQuantity = (int)$data['quantity'][$index];
                $product->stock = $newQuantity;
                $product->save();
            }
        }
        
    }
}