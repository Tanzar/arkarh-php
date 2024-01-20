<?php

namespace App\Classes\Abilities\Targeting\Primary;

use App\Classes\Abilities\Targeting\Abstracts\SelectStrategy;
use App\Classes\Units\Abstracts\Unit;

class HighestThreat extends SelectStrategy
{
    private int $currentValue = 0;

    protected function reset(): void
    {
        $this->currentValue = 0;
    }

    protected function checkUnit(Unit $unit): void
    {
        $threat = $unit->getThreat();
        if ($threat > $this->currentValue) {
            $this->currentValue = $threat;
            $this->setAsTarget($unit);
        }
    }

}