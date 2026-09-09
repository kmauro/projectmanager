<?php

namespace App\Domains\Files\Services\Strategies;

use App\Domains\Files\Services\FundingSourceMatcher;
use InvalidArgumentException;

class FundingSourceStrategy implements MatchInterface
{
    public function __construct(private FundingSourceMatcher $fundingSourceMatcher) {}

    public function matchId($selectedSources, $otherSources, $predefinedSources): array
    {
        if (! is_array($selectedSources) || ! is_iterable($predefinedSources)) {
            throw new InvalidArgumentException('Los datos de financiamiento no tienen el formato esperado.');
        }

        return $this->fundingSourceMatcher->match($selectedSources, $predefinedSources);
    }

}
