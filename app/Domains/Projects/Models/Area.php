<?php

namespace App\Domains\Projects\Models;

use Database\Factories\AreaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    /** @use HasFactory<AreaFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['area'];

    protected static function newFactory(): AreaFactory
    {
        return AreaFactory::new();
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
