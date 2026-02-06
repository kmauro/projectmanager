<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;
use App\Http\Controllers\StockController;
use App\Models\Product;
use App\Models\User;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;

//INDEX
Route::get('/', function () {
    /*if(auth()->check()){
        return redirect()->route('dashboard');
    }else{
        return view('auth.login');
    }*/
    return view('dashboard');
});

//USERS

Route::get('/user/create', function () {
    return view('user/create');
});

Route::post('/user/register', function () {
    $data = request()->validate([
        'user' => 'required|string|max:255|unique:users,user',
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'password' => 'required|string|min:8',
        'phone' => 'nullable|string|max:15',
        'access_level_id' => 'required|exists:access_levels,id',
    ], [
        'user.required' => 'El nombre de usuario es obligatorio.',
        'user.unique' => 'Este nombre de usuario ya está en uso.',
        'name.required' => 'El nombre completo es obligatorio.',
        'name.max' => 'El nombre no puede exceder 255 caracteres.',
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'Debe ingresar un correo electrónico válido.',
        'email.unique' => 'Este correo electrónico ya está registrado.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'access_level_id.required' => 'Debe seleccionar un nivel de acceso.',
        'access_level_id.exists' => 'El nivel de acceso seleccionado no es válido.',
    ]);
    
    $user = User::create($data);
    
    // Enviar email de verificación
    try {
        $user->sendEmailVerificationNotification();
        return redirect('user/create')->with('success', 'Usuario registrado exitosamente. Por favor, verifica tu correo electrónico.');
    } catch (\Exception $e) {
        // Si falla el envío del email, aún así guardamos el usuario
        return redirect('user/create')->with('success', 'Usuario registrado exitosamente. Error al enviar email de verificación, contacta al administrador.');
    }
});

Route::get('/user/verify-email/{token}', function ($token) {
    $hashedToken = hash('sha256', $token);
    
    $verification = DB::table('email_verification_tokens')
        ->where('token', $hashedToken)
        ->first();
    
    if (!$verification) {
        return redirect('user/create')->with('error', 'El enlace de verificación no es válido o ha expirado.');
    }
    
    $user = User::find($verification->user_id);
    
    if (!$user) {
        return redirect('user/create')->with('error', 'Usuario no encontrado.');
    }
    
    if ($user->email_verified_at) {
        return redirect('user/create')->with('info', 'Tu correo electrónico ya ha sido verificado anteriormente.');
    }
    
    // Verificar el email
    $user->markEmailAsVerified();
    
    return redirect('user/create')->with('success', '¡Correo electrónico verificado exitosamente!');
});

Route::get('/subcategories/{category}', [ProductController::class, 'getSubcategories']);

//STOCK
Route::get('/stock', function () {
    return view('stock.index', [
        'products' => StockController::getStock()
    ]);
});

Route::get('/stock/control', function () {
    return view('stock.control', [
        'products' => StockController::getQuantities()
    ]);
});

Route::post('/stock/control', function () {
        $data = request()->validate([
            'productid'  => 'required|array',
            'quantity' => 'required|array',
        ]);
        StockController::updateQuantities($data);
        return redirect('/stock')->with('success', 'Productos actualizados exitosamente.');
});

Route::get('/stock/add', function () {
    return view('stock.create');
});

//PRODUCTS
Route::get('/product/edit/{productId}', function ($productId) {
    return view('stock.edit', [
        'product' => Product::with('subcategory')->find($productId)
    ]);
});

Route::get('/product/delete/{productId}', function ($productId) {
    $product = StockController::getProduct($productId);
    if($product){
        return view('stock.delete', [
            'product' => $product
        ]);
    }else{
        return abort(404);
    }
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
    ProductController::addProduct($data);
    return redirect('/stock')->with('success', 'Producto agregado exitosamente.');
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
    if(ProductController::updateProduct($data, $productId)){
        return redirect('/stock')->with('success', 'Producto editado correctamente. ');
    }else{
        return redirect('/product/edit/'. $productId)->with('warning', 'Se produjo un error. ');
    }
    
});

Route::post('/product/delete/{productId}', function ($productId) {
    
    if(ProductController::deleteProduct($productId)){
        return redirect('/stock')->with('success', 'Producto eliminado correctamente. ');
    }else{
        return redirect('/product/edit/'. $productId)->with('warning', 'Se produjo un error. ');
    }
});


//SUPPLIERS

Route::get('/suppliers', function () {
    return view('suppliers.index', [
        'suppliers' => SupplierController::getSuppliers()
    ]);
});

Route::get('/suppliers/{productId}', function ($productId) {
    return view('suppliers.index', [
        'suppliers' => ProductController::getProductSuppliers($productId)
    ]);
});

Route::get('/supplier/add', function () {
    return view('suppliers.create');
});

Route::get('/supplier/edit/{supplierId}', function ($supplierId) {
    $supplier = SupplierController::getSupplier($supplierId);
    if($supplier){
        return view('suppliers.edit', [
            'supplier' => $supplier
        ]);
    }else{
        return abort(404);
    }
    
});

Route::get('/supplier/delete/{supplierId}', function ($supplierId) {
    $supplier = SupplierController::getSupplier($supplierId);
    if($supplier){
        return view('suppliers.delete', [
            'supplier' => $supplier
        ]);
    }else{
        return abort(404);
    }
});

Route::post('/supplier/delete/{supplierId}', function ($supplierId) {
    if(SupplierController::deleteSupplier($supplierId)){
        return redirect('/suppliers')->with('success', 'Proveedor eliminado correctamente. ');
    }else{
        return redirect('/suppliers')->with('warning', 'Se produjo un error. ');
    }
    
});

Route::post('/supplier/add', function () {
    $data = request()->validate([
        'name' => 'required|string|max:255',
        'cuit' => 'required|string|max:13',
        'phone' => 'required|string|max:15',
        'email' => 'required|email|max:255',
        'address' => 'required|string|max:255',
    ]);
    SupplierController::addSupplier($data);
    return redirect('/suppliers')->with('success', 'Proveedor agregado correctamente. ');
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
    if(SupplierController::updateSupplier($data, $supplierId)){
        return redirect('/suppliers')->with('success', 'Proveedor modificado correctamente. ');
    }else{
        return redirect('/suppliers')->with('warning', 'Se produjo un error. ');
    }
    
});

