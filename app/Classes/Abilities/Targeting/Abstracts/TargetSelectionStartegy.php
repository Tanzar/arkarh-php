<?php

namespace App\Classes\Abilities\Targeting\Abstracts;

use App\Classes\Combat\Side;
use App\Classes\Units\Abstracts\Unit;
use App\Classes\Units\Support\UnitsContainer;
use Illuminate\Support\Collection;

abstract class TargetSelectionStartegy
{
    public function selectTargets(Side $side, int $sourcePosition, int $range): Collection
    {
        $this->preSelect();
        $targets = new UnitsContainer();
        for ($i = 0; $i <= 2 * $range; $i++) {
            $position = $this->calcPosition($sourcePosition, $i);
            if ($position >= 0 && $position < $side->getWidth()) {
                $this->checkPosition($side, $position, $targets);
            }
        }
        $this->postSelect($targets);
        return $targets->getUnits();
    }

    protected abstract function preSelect(): void;

    protected final function calcPosition(int $sourcePosition, int $index): int
    {
        return $sourcePosition + ceil($index / 2) * (($index % 2) ? -1 : 1 );
    }

    protected abstract function checkPosition(Side $side, int $position, UnitsContainer $targets): void;

    protected abstract function postSelect(UnitsContainer $tragets): void;
}