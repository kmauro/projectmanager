<?php

namespace App\Services\Strategies;


use App\Repositories\FundingSourceRepository;


class FundingSourceStrategy implements MatchInterface
{

    public function matchId($selectedSources, $otherSources, $predefinedSources)
    {
        throw new \Exception('Not implemented');
    }

    
}