<?php

namespace App\Domains\Projects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FundingSource extends Model
{
    public $timestamps = false;

    protected $table = 'funding_sources';

    protected $fillable = ['name', 'type', 'predefined'];

    protected function casts(): array
    {
        return [
            'predefined' => 'boolean',
        ];
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'projects_funding_sources', 'source_id', 'project_id')
            ->withPivot('percentage');
    }
}
