<?php

namespace App\Classes\Abilities\Targeting\Enemies;

use App\Classes\Abilities\Targeting\Abstracts\SelectStrategy;
use App\Classes\Abilities\Targeting\Abstracts\Targeting;
use App\Classes\Combat\Battlefield;
use App\Classes\Modifiers\Category;
use App\Classes\Units\Abstracts\Unit;
use Illuminate\Support\Collection;

class Single implements Targeting
{
    private int $range;

    private SelectStrategy $primaryTargetSelect;

    public function __construct(SelectStrategy $primaryTargeting, int $range = 1)
    {
        $this->primaryTargetSelect = $primaryTargeting;
        $this->range = ($range >= 1) ? $range : 1;
    }

    public function select(Battlefield $battlefield, Unit $source): Collection
    {
        $side = $battlefield->getOppositeSide($source);
        $range = $source->getModifiedValue($this->range, Category::Range, 1);
        $target = $this->primaryTargetSelect->select($side, $source->getPosition(), $range, $source->prefersBack());
        if ($target !== null) {
            $targets = new Collection();
            $targets->push($target);
            return $targets;
        } else {
            return new Collection();
        }
    }
}