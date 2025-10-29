<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
   protected $fillable = [
       'name',
       'subcategory_id',
        'cost',
       'price',
       'stock',
       'min_stock',
   ];

    public function subcategory()
    {
         return $this->belongsTo(Subcategory::class);
    }

    public static function getData()
    {
        return self::with('subcategory.category')->paginate(15);
    }
    


    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'suppliers_products');
    }
}

?>