<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Supplier;
use App\Http\Controllers\ProductController;


//INDEX
Route::get('/', function () {
    /*if(auth()->check()){
        return redirect()->route('dashboard');
    }else{
        return view('auth.login');
    }*/
    return view('dashboard');
});

Route::get('/user', function () {
    return view('user');
});

Route::get('/subcategories/{category}', [ProductController::class, 'getSubcategories']);


Route::get('/stock', function () {
    return view('stock.index', [
        'products' => Product::getData()
    ]);
});

Route::get('/stock/control', function () {
    return view('stock.control', [
        'products' => Product::getQuantities()
    ]);
});

Route::post('/stock/control', function () {
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
    return redirect('/stock');
});

Route::get('/stock/add', function () {
    return view('stock.create');
});


Route::get('/stock/{productId}', function ($productId) {
    return view('stock.index', [
        'products' => Product::where('id', $productId)->get()
    ]);
}); 

Route::get('/product/edit/{productId}', function ($productId) {
    return view('stock.edit', [
        'product' => Product::with('subcategory')->find($productId)
    ]);
});

Route::get('/product/delete/{productId}', function ($productId) {
    return view('stock.delete', [
        'product' => Product::find($productId)
    ]);
});

Route::post('/product/add', function () {
    $data = request()->validate([
        'name' => 'required|string|max:255',
        'subcategory_id' => 'required|numeric',
        'cost' => 'required|numeric',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'min_stock' => 'required|integer',
    ]);
    Product::create($data);
    return redirect('/stock');
});

Route::post('/product/edit/{productId}', function ($productId) {
    // Handle form submission for editing an existing product
    $data = request()->validate([
        'name' => 'required|string|max:255',
        'subcategory_id' => 'required|numeric',
        'cost' => 'required|numeric',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'min_stock' => 'required|integer',
    ]);
    
    $product = Product::find($productId);
    $product->update($data);
    return redirect('/stock');
});

Route::post('/product/delete/{productId}', function ($productId) {
    $product = Product::find($productId);
    $product->delete();
    return redirect('/stock');
});


//SUPPLIERS

Route::get('/suppliers', function () {
    return view('suppliers.index', [
        'suppliers' => Supplier::paginate(10)
    ]);
});

Route::get('/suppliers/{productId}', function ($productId) {
    return view('suppliers.index', [
        'suppliers' => Product::find($productId)->suppliers()->paginate(10)
    ]);
});

Route::get('/supplier/add', function () {
    return view('suppliers.create');
});

Route::get('/supplier/edit/{supplierId}', function ($supplierId) {
    return view('suppliers.edit', [
        'supplier' => Supplier::find($supplierId)
    ]);
});

Route::get('/supplier/delete/{supplierId}', function ($supplierId) {
    return view('suppliers.delete', [
        'supplier' => Supplier::find($supplierId)
    ]);
});

Route::post('/supplier/delete/{supplierId}', function ($supplierId) {
    $supplier = Supplier::find($supplierId);
    $supplier->delete();
    return redirect('/suppliers');
});

Route::post('/supplier/add', function () {
    $data = request()->validate([
        'name' => 'required|string|max:255',
        'cuit' => 'required|string|max:13',
        'phone' => 'required|string|max:15',
        'email' => 'required|email|max:255',
        'address' => 'required|string|max:255',
    ]);
    Supplier::create($data);
    return redirect('/suppliers');
});

Route::post('/supplier/edit/{supplierId}', function ($supplierId) {
    // Handle form submission for editing an existing supplier
    $data = request()->validate([
        'name' => 'required|string|max:255',
        'cuit' => 'required|string|max:13',
        'phone' => 'required|string|max:15',
        'email' => 'required|email|max:255',
        'address' => 'required|string|max:255',
    ]);
    $supplier = Supplier::find($supplierId);
    $supplier->update($data);
    return redirect('/suppliers');
});

