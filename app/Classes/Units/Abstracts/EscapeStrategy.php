<?php

namespace App\Classes\Units\Abstracts;

interface EscapeStrategy
{
    public function canFight(Unit $unit, bool $onReserve): bool;
}