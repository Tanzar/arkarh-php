<?php

namespace App\Classes\Abilities\Targeting\Abstracts;

use App\Classes\Combat\Side;
use App\Classes\Units\Abstracts\Unit;
use App\Classes\Units\Support\UnitsContainer;
use Illuminate\Support\Collection;

abstract class TargetSelectionStartegy
{
    private ?Unit $priamryTarget = null;

    private bool $bothLines = false;

    public function checkBothLines(bool $check): void
    {
        $this->bothLines = $check;
    }

    protected function setPrimaryTarget(Unit $unit): void
    {
        $this->priamryTarget = $unit;
    }

    protected function getPrimaryTarget(): ?Unit
    {
        return $this->priamryTarget;
    }

    public function selectTargets(Side $side, int $sourcePosition, int $range, int $area): Collection
    {
        $this->findPrimaryTarget($side, $sourcePosition, $range);
        if ($this->priamryTarget !== null) {
            return $this->selectTargetsInArea($side, $area);
        }
        return new Collection();
    }

    private function findPrimaryTarget(Side $side, int $sourcePosition, int $range): void
    {
        $this->prePrimarySelect();
        for ($i = 0; $i <= 2 * $range; $i++) {
            $position = $this->calcPosition($sourcePosition, $i);
            if ($position >= 0 && $position < $side->getWidth()) {
                $front = $side->getFront()->get($position);
                if ($front !== null && $front->isAlive()) {
                    $this->checkForPrimary($front);
                }
                if ($front === null || $front->isDead() || $this->bothLines) {
                    $back = $side->getBack()->get($position);
                    if ($back !== null) {
                        $this->checkForPrimary($back);
                    }
                }
            }
        }
        $this->postPrimarySelect();
    }

    protected abstract function prePrimarySelect(): void;

    protected abstract function checkForPrimary(Unit $unit): void;

    protected abstract function postPrimarySelect(): void;

    private function selectTargetsInArea(Side $side, int $area): Collection
    {
        $this->preInAreaSelect();
        $targets = new UnitsContainer();
        for ($i = 0; $i <= $area * 2; $i++) {
            $position = $this->calcPosition($this->priamryTarget->getPosition(), $i); 
            if ($position >= 0 && $position < $side->getWidth()) {
                $front = $side->getFront()->get($position);
                if ($front !== null && $front->isAlive()) {
                    $this->checkInArea($front, $targets);
                }
                if ($front === null || $front->isDead() || $this->bothLines) {
                    $back = $side->getBack()->get($position);
                    if ($back !== null) {
                        $this->checkInArea($back, $targets);
                    }
                }
            }
        }
        $this->postInAreaSelect($targets);
        return $targets->getUnits();
    }

    protected abstract function preInAreaSelect(): void;

    protected abstract function checkInArea(Unit $unit, UnitsContainer $targets): void;

    protected abstract function postInAreaSelect(UnitsContainer $targets): void;

    protected final function calcPosition(int $sourcePosition, int $index): int
    {
        return $sourcePosition + ceil($index / 2) * (($index % 2) ? -1 : 1 );
    }
}