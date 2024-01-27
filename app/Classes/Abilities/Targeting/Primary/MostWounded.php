<?php

namespace App\Classes\Abilities\Targeting\Primary;
use App\Classes\Abilities\Targeting\Abstracts\SelectStrategy;
use App\Classes\Units\Abstracts\Unit;

class MostWounded extends SelectStrategy
{
    private float $lowestPercentage = 100;

    protected function reset(): void
    {
        $this->lowestPercentage = 100;
    }

    protected function checkUnit(Unit $unit): void
    {
        $percentage = $unit->getHealthPercentage();
        if ($percentage < $this->lowestPercentage) {
            $this->setAsTarget($unit);
            $this->lowestPercentage = $percentage;
        }
    }

}