<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierProduct extends Model
{

    
    /** @use HasFactory<\Database\Factories\SupplierProductFactory> */
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'supplier_id',
        'product_id',
    ];

    protected $table = 'suppliers_products';

}
