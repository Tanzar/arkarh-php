<?php

namespace App\Classes\Abilities\Targeting\Primary;

use App\Classes\Abilities\Targeting\Abstracts\SelectStrategy;
use App\Classes\Units\Abstracts\Unit;

class LowestHealth extends SelectStrategy
{

    private int $currentValue = PHP_INT_MAX;

    protected function reset(): void 
    {
        $this->currentValue = PHP_INT_MAX;
    }

    protected function checkUnit(Unit $unit): void
    {
        $health = $unit->getHealth();
        if ($health < $this->currentValue) {
            $this->currentValue = $health;
            $this->setAsTarget($unit);
        }
    }

}