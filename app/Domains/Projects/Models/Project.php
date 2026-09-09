<?php

namespace App\Domains\Projects\Models;

use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'objective',
        'innovation_value',
        'trl_level_id',
        'area_id',
        'located_entity',
        'province_id',
        'EBCT_amount',
        'countries',
    ];

    protected function casts(): array
    {
        return [
            'SID' => 'integer',
            'time_sent' => 'datetime',
            'time_completed' => 'datetime',
            'time_modified' => 'datetime',
            'start_date' => 'date:Y-m-d',
            'end_date' => 'date:Y-m-d',
        ];
    }

    protected static function newFactory(): ProjectFactory
    {
        return ProjectFactory::new();
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function trlLevel(): BelongsTo
    {
        return $this->belongsTo(TRLLevel::class);
    }

    public function techs(): BelongsToMany
    {
        return $this->belongsToMany(Tech::class, 'projects_techs');
    }

    public function needs(): BelongsToMany
    {
        return $this->belongsToMany(Need::class, 'projects_needs');
    }

    public function fundingSources(): BelongsToMany
    {
        return $this->belongsToMany(FundingSource::class, 'projects_funding_sources', 'project_id', 'source_id')
            ->withPivot('percentage');
    }

    public function sectors(): BelongsToMany
    {
        return $this->belongsToMany(Sector::class, 'projects_sectors');
    }
}
