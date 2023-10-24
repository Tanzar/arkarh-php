<?php

namespace App\Classes\Units\Escape;
use App\Classes\Units\Abstracts\EscapeStrategy;
use App\Classes\Units\Abstracts\Unit;

class Standard implements EscapeStrategy
{

    public function canFight(Unit $unit, bool $onReserve): bool
    {
        if ($unit->isAlive()) {
            if ($onReserve) {
                return $unit->getMorale() >= 500; 
            } else {
                return $unit->getMorale() >= 50;
            }
        } else {
            return false;
        }
    }
}