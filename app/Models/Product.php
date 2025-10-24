<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
   protected $fillable = [
       'name',
       'description',
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
        return self::with('subcategory.category')->get();
    }

}

?>