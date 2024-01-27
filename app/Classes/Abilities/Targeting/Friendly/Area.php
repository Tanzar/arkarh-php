<?php

namespace App\Classes\Abilities\Targeting\Friendly;

use App\Classes\Abilities\Targeting\Abstracts\SelectStrategy;
use App\Classes\Abilities\Targeting\Abstracts\Targeting;
use App\Classes\Abilities\Targeting\Primary\MostWounded;
use App\Classes\Combat\Battlefield;
use App\Classes\Combat\PositionFormula;
use App\Classes\Combat\Side;
use App\Classes\Modifiers\Category;
use App\Classes\Units\Abstracts\Unit;
use Illuminate\Support\Collection;

class Area implements Targeting
{
    private int $range;
    private int $radius;

    private SelectStrategy $primaryTargetSelect;

    public function __construct(int $range = 1,int $radius = 0) {
        $this->primaryTargetSelect = new MostWounded();
        $this->range = ($range >= 1) ? $range : 1;
        $this->radius = ($radius >= 0) ? $radius : 0;
    }

    public function select(Battlefield $battlefield, Unit $source): Collection
    {
        $side = $battlefield->getFriendlySide($source);
        $range = $source->getModifiedValue($this->range, Category::Range, 1);
        $primaryTarget = $this->primaryTargetSelect->select($side, $source->getPosition(), $range, $source->prefersBack());
        if ($primaryTarget !== null) {
            return $this->getTargetsInArea($side, $primaryTarget);
        } else {
            return new Collection();
        }
    }

    private function getTargetsInArea(Side $side, Unit $primaryTarget): Collection
    {
        $targets = new Collection();
        $startPosition = $primaryTarget->getPosition();
        $front = $side->getFront();
        $back = $side->getBack();
        for ($i = 0; $i <= $this->radius * 2; $i++) {
            $position = PositionFormula::calc($startPosition, $i);
            $unit = $front->get($position);
            if ($unit !== null && $unit->isAlive()) {
                $targets->push($unit);
            }
            $unit = $back->get($position);
            if ($unit !== null && $unit->isAlive()) {
                $targets->push($unit);
            }
        }
        return $targets;
    }
}