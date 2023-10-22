<?php

namespace App\Classes\Game;

use App\Classes\Factions\Abstracts\Faction;

interface FactionsInterface
{
    public function add(string $scriptName, Faction $action): void;
}