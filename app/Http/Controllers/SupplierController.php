<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{

    public static function addSupplier($data){
        Supplier::create($data);
    }

    public static function getSuppliers(){
        return Supplier::paginate(10);
    }

    public static function getSupplier($supplierId){
        return Supplier::find($supplierId);

    }

    public static function deleteSupplier($supplierId){
        $supplier = Supplier::find($supplierId);
        if($supplier){
            $supplier->delete();
            return true;
        }else{
            return false;
        }
        
    }

    public static function updateSupplier($data, $supplierId){
        $supplier = Supplier::find($supplierId);
        if($supplier){
            $supplier->update($data);    
            return true;
        }else{
            return false;
        }
        
    }

}
