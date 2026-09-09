<?php

namespace App\Domains\Projects\Models;

use Database\Factories\TRLLevelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TRLLevel extends Model
{
    /** @use HasFactory<TRLLevelFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $table = 'trl_levels';

    protected $fillable = ['trl_level'];

    protected static function newFactory(): TRLLevelFactory
    {
        return TRLLevelFactory::new();
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
