<?php

namespace App\Classes\Abilities\Targeting\Enemies;

use App\Classes\Abilities\Targeting\Primary\SelectStrategy;
use App\Classes\Combat\Battlefield;
use App\Classes\Combat\PositionFormula;
use App\Classes\Combat\Side;
use App\Classes\Modifiers\Category;
use App\Classes\Units\Abstracts\Unit;
use Illuminate\Support\Collection;

class Area
{
    private SelectStrategy $primaryTargetSelect;

    public function __construct(SelectStrategy $primaryTargeting)
    {
        $this->primaryTargetSelect = $primaryTargeting;
    }

    public function select(Battlefield $battlefield, Unit $source, int $baseRange, int $baseArea, bool $bothLines): Collection
    {
        $side = $battlefield->getOppositeSide($source);
        $range = $source->getModifiedValue($baseRange, Category::Range, 1);
        $primaryTarget = $this->primaryTargetSelect->select($side, $source->getPosition(), $range, $source->prefersBack());
        if ($primaryTarget !== null) {
            return $this->getTargetsInArea($side, $primaryTarget, $baseArea, $bothLines);
        } else {
            return new Collection();
        }
    }

    private function getTargetsInArea(Side $side, Unit $primaryTarget, int $area, bool $bothLines): Collection
    {
        $targets = new Collection();
        $startPosition = $primaryTarget->getPosition();
        $front = $side->getFront();
        $back = $side->getBack();
        for ($i = 0; $i <= $area * 2; $i++) {
            $position = PositionFormula::calc($startPosition, $i);
            $unit = $front->get($position);
            if ($unit !== null && $unit->isAlive()) {
                $targets->push($unit);
            }
            if ($bothLines || $unit === null || $unit->isDead()) {
                $unit = $back->get($position);
                if ($unit !== null && $unit->isAlive()) {
                    $targets->push($unit);
                }
            }
        }
        return $targets;
    }
}