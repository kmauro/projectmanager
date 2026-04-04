<?php

namespace App\Services\Strategies;

interface MatchInterface
{
    public function matchId($selectedIds, $otherIds, $predefinedIds);
}
