<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;


class StockController extends Controller
{

    //GET Methods
    public function show()
    {
        $this->authorize('viewAny', Product::class);
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
        $this->authorize('control', Product::class);
        $data = $request->validate([
            'productid'  => 'required|array',
            'productid.*' => 'required|numeric|exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:0',

        ]);
        DB::transaction(function () use ($data){
            $products = Product::whereIn('id', $data['productid'])->get()->keyBy('id');
            foreach ($data['productid'] as $index => $productId) {
                if (!empty($products[$productId])) {
                    $newQuantity = (int)$data['quantity'][$index];
                    $products[$productId]->stock = $newQuantity;
                    $products[$productId]->save();
                }
            }
        });    
        return redirect('/stock')->with('success', 'Productos actualizados exitosamente.');
    }
}