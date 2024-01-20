<?php

namespace App\Classes\Factions\Abstracts;

use App\Classes\Units\Abstracts\Unit;
use App\Classes\Units\Abstracts\UnitPattern;
use App\Classes\Units\Exceptions\UnitNotFoundException;
use Illuminate\Support\Collection;

abstract class Faction implements FactionUnitsInterface
{

    private Collection $unitsPatterns;

    public function __construct()
    {
        $this->unitsPatterns = collect();
    }

    public function getUnit(string $scriptName): Unit
    {
        if ($this->unitsPatterns->count() === 0) {
            $this->units($this);
        }
        $pattern = $this->getUnitPattern($scriptName);
        return $pattern->make();
    }
    
    protected abstract function units(FactionUnitsInterface $units): void;

    public function add(string $scriptName, UnitPattern $unit): void 
    {
        if($this->unitsPatterns->doesntContain($scriptName)) {
            $this->unitsPatterns->put($scriptName, $unit);
        }
    }

    private function getUnitPattern(string $scriptName): UnitPattern
    {
        $pattern = $this->unitsPatterns->get($scriptName);
        if($pattern === null) {
            throw new UnitNotFoundException($scriptName);
        }
        return $pattern;
    }

    public abstract function canPlayerSelect(): bool;

}