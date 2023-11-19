<?php

namespace App\Classes\Combat;
use App\Classes\Units\Abstracts\Unit;
use Illuminate\Support\Collection;

class Side
{
    private Line $front;
    
    private Line $back;

    private Collection $graveyard;

    private Collection $reserves;

    private int $combatWidth = 15;

    private int $healReserves = 1;

    private int $morale = 10;

    private Collection $plannedUnits;

    public function __construct(ArmyPatterns $armies)
    {
        $this->front = new Line($this->combatWidth, true);
        $this->back = new Line($this->combatWidth, false);
        $this->graveyard = new Collection();
        $this->reserves = new Collection();
        $this->plannedUnits = new Collection();
        $this->positionUnits($armies);
    }

    private function positionUnits(ArmyPatterns $armies): void
    {
        /** @var ArmyPattern $army */
        foreach ($armies->getPatterns() as $army) {
            $this->setupLine($army->getFront(), $this->front);
            $this->setupLine($army->getBack(), $this->back);
            $this->setupReserves($army);
        }
    }

    private function setupLine(array $units, Line $line) : void
    {
        foreach ($$units as $position => $unit) {
            if ($unit !== null) {
                $line->add($position, $unit);
                if ($this->plannedUnits->doesntContain($unit->getId())) {
                    $this->plannedUnits->add($unit->getId());
                }
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
    public function getFront(): Line
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
    public function getBack(): Line
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
        /** @var Unit $unit */
        foreach ($this->front as $position => $unit) {
            if ($unit !== null) {
                $units->add($unit);
            }
        }
        foreach ($this->back as $position => $unit) {
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
        $this->regenerateReserves();
        $canFrontFight = $this->checkLine($this->front, true);
        $canBackFight = $this->checkLine($this->back, false);
        return $canBackFight || $canFrontFight;
    }

    private function regenerateReserves(): void
    {
        /** @var Unit $unit */
        foreach ($this->reserves as $unit) {
            $unit->heal($this->healReserves);
            $unit->increaseMorale($this->morale);
        }
    }

    private function checkLine(Line $line, bool $front): bool
    {
        $canFight = false;
        /** @var ?Unit $unit */
        foreach ($line as $position => $unit) {
            if ($unit === null || !$unit->canFight(false)) {
                $replacement = $this->getReserveUnit($unit, $line->isFront());
                if ($unit->isAlive()) {
                    $this->reserves->add($unit);
                } else {
                    $this->graveyard->add($unit);
                }
                if ($replacement !== null) {
                    $canFight = true;
                    $line->add($position, $replacement);
                }
            }
        }
        return $canFight;
    }

    private function getReserveUnit(?Unit $unit, bool $front): ?Unit
    {
        $replacementKey = null;
        $replacementUnit = null;
        foreach ($this->reserves as $key => $replacement) {
            if ($this->canReplace($replacement, $front)) {
                if ($unit === null && $this->isNotPlanned($replacement)){
                    $replacementKey = $key;
                    $replacementUnit = $replacement;
                    break;
                } elseif (
                    $unit !== null &&
                    (
                        $replacement->getId() === $unit->getId() ||
                        $this->isNotPlanned($replacement)
                    )
                ) {
                    $replacementKey = $key;
                    $replacementUnit = $replacement;
                    break;
                }
            }
        }
        if ($replacementUnit !== null) {
            $this->reserves->forget($replacementKey);
        }
        return $replacementUnit;
    }

    private function canReplace(Unit $replacement, bool $front): bool
    {
        return $replacement->canFight(true) && $replacement->prefersFront() === $front;
    }

    private function isNotPlanned(Unit $unit): bool
    {
        return $this->plannedUnits->doesntContain($unit->getId());
    }
}