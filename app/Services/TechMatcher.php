<?php

namespace App\Services;

use App\Repositories\TechRepository;

class TechMatcher
{
    public function resolveTechIds(string $selectedTechs, string $otherTech, iterable $predefinedTechs): array
    {
        $ids = $this->matchPredefinedTechs($selectedTechs, $predefinedTechs);

        if ($this->hasOtherTech($selectedTechs, $otherTech)) {
            $otherId = TechRepository::saveOtherTech($otherTech);
            $ids[] = $otherId;
        }

        return $ids;
    }

    private function matchPredefinedTechs(string $selectedTechs, iterable $predefinedTechs): array
    {
        $ids = [];

        foreach ($predefinedTechs as $tech) {
            if (str_contains($selectedTechs, $tech->name)) {
                $ids[] = $tech->id;
            }
        }

        return $ids;
    }

    private function hasOtherTech(string $selectedTechs, string $otherTech): bool
    {
        return str_contains($selectedTechs, 'Otro') && !empty($otherTech);
    }
}
