<?php

namespace App\Domains\Projects\Models;

use Database\Factories\ProvinceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    /** @use HasFactory<ProvinceFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['province'];

    protected static function newFactory(): ProvinceFactory
    {
        return ProvinceFactory::new();
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
