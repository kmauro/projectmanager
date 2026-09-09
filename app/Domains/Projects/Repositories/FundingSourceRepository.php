<?php

namespace App\Domains\Projects\Repositories;

use App\Domains\Projects\Models\FundingSource;
use Illuminate\Database\Eloquent\Collection;

class FundingSourceRepository
{
    public function getFinancialSources(): Collection
    {
        return FundingSource::all();
    }

    public function getFundingSourcesForMatching(): Collection
    {
        return FundingSource::query()
            ->select(['id', 'name', 'type', 'predefined'])
            ->orderBy('id')
            ->get();
    }

    public function firstOrCreateCustom(string $name, string $type): int
    {
        $fundingSource = FundingSource::query()->firstOrCreate(
            ['name' => $name, 'type' => $type],
            ['predefined' => false],
        );

        return (int) $fundingSource->id;
    }
}
