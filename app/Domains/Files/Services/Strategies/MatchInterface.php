<?php

namespace App\Domains\Files\Services\Strategies;

interface MatchInterface
{
    public function matchId($selectedIds, $otherIds, $predefinedIds);
}
