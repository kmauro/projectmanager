<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{

    //GET Methods
    public function getSuppliers()
    {
        return view('suppliers.index', ['suppliers' => Supplier::paginate(10)]);
    }

    public function getAdd()
    {
        return view('suppliers.create');
    }

    public function getUpdate($supplierId)
    {
        $supplier = Supplier::find($supplierId);
        if(!$supplier){
            abort(404);
        }
        return view('suppliers.edit', ['supplier' => $supplier]);
    }

    public function getDelete($supplierId)
    {
        $supplier = Supplier::find($supplierId);
        if(!$supplier){
            abort(404);
        }
        return view('suppliers.delete', ['supplier' => $supplier]);
    }

    public function getSupplierProducts($supplierId)
    {
        $supplier = Supplier::find($supplierId);
        if (!$supplier) {
            abort(404);
        }
        $products = $supplier->products()->with('subcategory.category')->paginate(10);
        return view('stock.index', ['products' => $products]);
    }


    //POST Methods
    public function addSupplier(Request $request)
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'cuit' => 'required|string|unique:suppliers|max:13',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
        ]);
        if(Supplier::create($data)){
            return redirect('/suppliers')->with('success', 'Proveedor agregado correctamente. ');
        }else{
            return redirect('/supplier/add')->with('warning', 'Se produjo un error. ');
        }
    }

    public function updateSupplier(Request $request, $supplierId)
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'cuit' => 'required|string|max:13',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
        ]);
        $supplier = Supplier::find($supplierId);
        if($supplier){
            $supplier->update($data);    
            return redirect('/suppliers')->with('success', 'Proveedor modificado correctamente. ');
        }else{
            return redirect('/suppliers')->with('warning', 'Se produjo un error. ');
        }
        
    }

    public function deleteSupplier($supplierId)
    {
        $supplier = Supplier::find($supplierId);
        if($supplier){
            $supplier->delete();
            return redirect('/suppliers')->with('success', 'Proveedor eliminado correctamente. ');
        }else{
            return redirect('/suppliers')->with('warning', 'Se produjo un error. ');
        }
        
    }

}
