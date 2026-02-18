<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    
    //GET Methods
    public function getAdd()
    {
        $this->authorize('create', Product::class);
        return view('product.create');
    }

    public function getUpdate($productId)
    {
        $product = Product::with('subcategory')->find($productId);
        $this->authorize('update', $product);
        
        if (!$product) {
            abort(404);
        }
        return view('product.edit', ['product' => $product]);
    }

    public function getDelete($productId)
    {
        $product = Product::find($productId);
        $this->authorize('delete', $product);
        if (!$product) {
            abort(404);
        }
        return view('product.delete', ['product' => $product]);
    }

    public function getProductSuppliers($productId)
    {
        $product = Product::find($productId);
        $this->authorize('view', $product);
        if (!$product) {
            abort(404);
        }
        return view('suppliers.index', ['suppliers' => $product->suppliers()->paginate(10)]);
    }

    //POST Methods
    public function addProduct(Request $request)
    {
        $this->authorize('create');
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'subcategory_id' => 'required|numeric',
            'cost' => 'required|numeric',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'min_stock' => 'required|integer',
        ]);
        $product = Product::create($data);
        if ($product){
            $product->suppliers()->sync($request->input('suppliers', []));    
            return redirect('/stock')->with('success', 'Producto agregado exitosamente.');
        }else{
            return redirect('/product/add')->with('warning', 'Se produjo un error. ');
        }
    }

    public function updateProduct(Request $request, $productId)
    {
        $data = request()->validate([
        'name' => 'required|string|max:255',
        'subcategory_id' => 'required|numeric',
        'cost' => 'required|numeric',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'min_stock' => 'required|integer',
    ]);
        $product = Product::find($productId);
        $this->authorize('update', $product);
        if($product){
            $product->update($data);
            $product->suppliers()->sync($request->input('suppliers', []));
            return redirect('/stock')->with('success', 'Producto editado correctamente. ');
        }else{
            return redirect('/product/edit/'. $productId)->with('warning', 'Se produjo un error. ');
        }
        
    }

    public function deleteProduct($productId)
    {
        $product = Product::find($productId);
        $this->authorize('delete', $product);
        if($product){
            $product->delete();
            return redirect('/stock')->with('success', 'Producto eliminado correctamente. ');
        }else{
            return redirect('/product/edit/'. $productId)->with('warning', 'Se produjo un error. ');
        }
    }

    // Other Methods
    public function getSubcategories($categoryId)
    {
        $subcategories = Category::find($categoryId)->subcategories;
        if(!$subcategories){
            return response()->json([], 404);
        }
        return response()->json($subcategories);
    }
}
