<?php

namespace App\Services\Strategies;

use App\Services\Strategies\MatchInterface;

class MatcherContext
{
    protected MatchInterface $strategy;

    public function setStrategy(MatchInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    public function handle($selectedIds, $otherIds, $predefinedIds, $projectId)
    {
        $items = $this->strategy->matchId($selectedIds, $otherIds, $predefinedIds);
        return $items;
    }
}