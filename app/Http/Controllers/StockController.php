<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;


class StockController extends Controller
{

    //GET Methods
    public function show()
    {
        return view('stock.index', [
            'products' => Product::with('subcategory.category')->paginate(15)]);

    }

    public function control()
    {
        $this->authorize('control', Product::class);
        return view('stock.control', ['products' => Product::select('id', 'name', 'stock', 'min_stock')->get()]);
    }

    
    //POST Methods
    public function updateStock(Request $request)
    {    
        $data = request()->validate([
            'productid'  => 'required|array',
            'quantity' => 'required|array',
        ]);
        foreach ($data['productid'] as $index => $productId) {
            $product = Product::find($productId);
            if ($product) {
                $newQuantity = (int)$data['quantity'][$index];
                $product->stock = $newQuantity;
                $product->save();
            }
        }        
        return redirect('/stock')->with('success', 'Productos actualizados exitosamente.');
    }
}