<?php

namespace App\Classes\Factions\Abstracts;

use App\Classes\Units\Abstracts\UnitPattern;
use Illuminate\Support\Collection;

abstract class Faction implements FactionUnitsInterface
{

    private Collection $unitsPatterns;

    private bool $playable;

    public function __construct()
    {
        $this->unitsPatterns = collect();
        $this->units($this);
        $this->playable = $this->canPlayerSelect();
    }

    protected abstract function units(FactionUnitsInterface $units): void;

    public function add(string $scriptName, UnitPattern $unit): void 
    {
        if($this->unitsPatterns->doesntContain($scriptName)) {
            $this->unitsPatterns->put($scriptName, $unit);
        }
    }

    protected abstract function canPlayerSelect(): bool;

    public function isPlayable(): bool
    {
        return $this->playable;
    }

}