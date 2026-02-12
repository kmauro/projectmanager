<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    
    //GET Methods
    public function getAdd()
    {
        return view('product.create');
    }

    public function getUpdate($productId)
    {
        $product = Product::with('subcategory')->find($productId);
        if (!$product) {
            abort(404);
        }
        return view('product.edit', ['product' => $product]);
    }

    public function getDelete($productId)
    {
        $product = Product::find($productId);
        if (!$product) {
            abort(404);
        }
        return view('product.delete', ['product' => $product]);
    }

    public function getProductSuppliers($productId)
    {
        $product = Product::find($productId);
        if (!$product) {
            abort(404);
        }
        return view('suppliers.index', ['suppliers' => $product->suppliers()->paginate(10)]);
    }

    //POST Methods
    public function addProduct(Request $request)
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'subcategory_id' => 'required|numeric',
            'cost' => 'required|numeric',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'min_stock' => 'required|integer',
        ]);
        if (Product::create($data)){
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
        if($product){
            $product->update($data);
            return redirect('/stock')->with('success', 'Producto editado correctamente. ');
        }else{
            return redirect('/product/edit/'. $productId)->with('warning', 'Se produjo un error. ');
        }
        
    }

    public function deleteProduct($productId)
    {
        $product = Product::find($productId);
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
        $category = Category::find($categoryId);
        if(!$category){
            return response()->json([], 404);
        }
        $subcategories = Category::find($categoryId)->subcategories;
        return response()->json($subcategories);
    }
}
