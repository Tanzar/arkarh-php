<?php

namespace App\Classes\Factions\Abstracts;

use App\Classes\Units\Abstracts\Unit;
use App\Classes\Units\Abstracts\UnitPattern;
use App\Classes\Units\Exceptions\UnitNotFoundException;
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

    public function getUnit(string $scriptName): Unit
    {
        $pattern = $this->getUnitPattern($scriptName);
        return $pattern->make();
    }

    private function getUnitPattern(string $scriptName): UnitPattern
    {
        $pattern = $this->unitsPatterns->get($scriptName);
        if($pattern === null) {
            throw new UnitNotFoundException($scriptName);
        }
        return $pattern;
    }

    protected abstract function canPlayerSelect(): bool;

    public function isPlayable(): bool
    {
        return $this->playable;
    }

}