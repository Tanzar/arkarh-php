<?php

namespace App\Classes\Abilities\Targeting;
use App\Classes\Abilities\Targeting\Abstracts\TargetSelectionStartegy;
use App\Classes\Units\Abstracts\Unit;
use App\Classes\Units\Support\UnitsContainer;

class TargetByThreat extends TargetSelectionStartegy
{
    private int $mainTargetThreat = 0;

    protected function prePrimarySelect(): void
    {
        $this->mainTargetThreat = 0;
    }

    protected function checkForPrimary(Unit $unit): void
    {
        $threat = $unit->getThreat();
        if ($threat > $this->mainTargetThreat) {
            $this->mainTargetThreat = $threat;
            $this->setPrimaryTarget($unit);
        }
    }

    protected function postPrimarySelect(): void
    {

    }

    protected function preInAreaSelect(): void { }

    protected function checkInArea(Unit $unit, UnitsContainer $targets): void
    {
        $targets->addUnit($unit);
    }

    protected function postInAreaSelect(UnitsContainer $targets): void { }

}