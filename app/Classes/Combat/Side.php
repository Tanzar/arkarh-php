<?php

namespace App\Classes\Combat;
use App\Classes\Units\Abstracts\Unit;
use Illuminate\Support\Collection;

class Side
{
    private Collection $front;
    
    private Collection $back;

    private Collection $graveyard;

    private Collection $reserves;

    private int $combatWidth = 15;

    public function __construct(ArmyPatterns $armies)
    {
        $this->front = $this->formLine();
        $this->back = $this->formLine();
        $this->graveyard = new Collection();
        $this->reserves = new Collection();
        $this->positionUnits($armies);
    }

    private function formLine(): Collection
    {
        $line = new Collection();
        for ($i = 0; $i < $this->combatWidth; $i++) {
            $line->put($i, null);
        }
        return $line;
    }

    private function positionUnits(ArmyPatterns $armies): void
    {
        /** @var ArmyPattern $army */
        foreach ($armies->getPatterns() as $army) {
            $this->setupFront($army);
            $this->setupBack($army);
            $this->setupReserves($army);
        }
    }

    private function setupFront(ArmyPattern $army) : void
    {
        $front = $army->getFront();
        foreach ($front as $position => $unit) {
            if ($this->front->get($position) === null) {
                $this->front->put($position, $unit);
            } else {
                $this->reserves->add($unit);
            }

        }
    }

    private function setupBack(ArmyPattern $army) : void
    {
        $back = $army->getBack();
        foreach ($back as $position => $unit) {
            if ($this->back->get($position) === null) {
                $this->back->put($position, $unit);
            } else {
                $this->reserves->add($unit);
            }

        }
    }

    private function setupReserves(ArmyPattern $army) : void
    {
        $reserve = $army->getReserve();
        foreach ($reserve as $unit) {
            $this->reserves->add($unit);
        }
    }


    /**
     * Get the value of front
     */ 
    public function getFront(): Collection
    {
        return $this->front;
    }

    public function getFrontUnit(int $position): ?Unit
    {
        return $this->front->get($position);
    }

    /**
     * Get the value of back
     */ 
    public function getBack(): Collection
    {
        return $this->back;
    }

    public function getBackUnit(int $position): ?Unit
    {
        return $this->back->get($position);
    }

    /**
     * Get the value of graveyard
     */ 
    public function getGraveyard(): Collection
    {
        return $this->graveyard;
    }

    /**
     * Get the value of reserves
     */ 
    public function getReserves(): Collection
    {
        return $this->reserves;
    }

    public function getWidth(): int
    {
        return $this->combatWidth;
    }

    public function getUnitsBySpeed(): Collection
    {
        $units = new Collection();
        for ($i = 0; $i < $this->combatWidth; $i++) {
            $unit = $this->front->get($i);
            if ($unit !== null) {
                $units->add($unit);
            }
            $unit = $this->back->get($i);
            if ($unit !== null) {
                $units->add($unit);
            }
        }
        $units->sortBy(function (Unit $unit, $key) {
            return $unit->getSpeed();
        });
        return $units;
    }

    /**
     * Refreshes side
     *
     * @return boolean true if side is capable of fighting, false if side cannot fight anymore
     */
    public function refreshUnits(): bool
    {
        //return false;
    }


}