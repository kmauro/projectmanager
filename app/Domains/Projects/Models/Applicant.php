<?php

namespace App\Domains\Projects\Models;

use Database\Factories\ApplicantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Applicant extends Model
{
    /** @use HasFactory<ApplicantFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'IP_address',
        'user',
        'full_name',
        'email',
        'phone',
        'cuil',
        'institution_id',
        'company_name',
        'company_CUIT',
    ];

    protected static function newFactory(): ApplicantFactory
    {
        return ApplicantFactory::new();
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
