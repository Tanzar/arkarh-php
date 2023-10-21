<?php

namespace App\Classes\Factions\Abstracts;

use App\Classes\Units\Abstracts\UnitPattern;

interface FactionUnitsInterface 
{
    public function add(string $scriptName, UnitPattern $unit): void;
}