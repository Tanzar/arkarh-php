<?php

namespace App\Classes\Abilities\Targeting\Friendly;
use App\Classes\Abilities\Targeting\Abstracts\Targeting;
use App\Classes\Combat\Battlefield;
use App\Classes\Combat\PositionFormula;
use App\Classes\Combat\Side;
use App\Classes\Modifiers\Category;
use App\Classes\Units\Abstracts\Unit;
use Illuminate\Support\Collection;

class Count implements Targeting
{
    private int $range;

    private int $count;

    public function __construct(int $range, int $count)
    {
        $this->range = $range;
        $this->count = $count;
    }

    public function select(Battlefield $battlefield, Unit $source): Collection
    {
        $side = $battlefield->getFriendlySide($source);
        $range = $source->getModifiedValue($this->range, Category::Range, 1);
        $validTargets = $this->getValidTargets($side, $range, $source);
        $targets = new Collection();
        /** @vat Unit $target */
        foreach ($validTargets as $target) {
            $targets->push($target);
            if ($targets->count() >= $this->count) {
                break;
            }
        }

        return $targets;
    }

    private function getValidTargets(Side $side, int $range, Unit $source): Collection
    {
        $validTargets = new Collection();
        $front = $side->getFront();
        $back = $side->getBack();
        /** @var ?Unit $unit */
        for ($i = 0; $i <= 2 * $range; $i++) {
            $position = PositionFormula::calc($source->getPosition(), $i);
            $unit = $front->get($position);
            if ($unit !== null && $unit->getHealthPercentage() < 100) {
                $validTargets->add($unit);
            }
            $unit = $back->get($position);
            if ($unit !== null && $unit->getHealthPercentage() < 100) {
                $validTargets->add($unit);
            }
        }
        return $validTargets->sortByDesc(function (Unit $unit) {
            return $unit->getHealthPercentage();
        });
    }

}