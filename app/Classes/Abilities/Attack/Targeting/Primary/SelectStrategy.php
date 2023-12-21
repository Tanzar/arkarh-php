<?php

namespace App\Classes\Abilities\Attack\Targeting\Primary;

use App\Classes\Combat\PositionFormula;
use App\Classes\Combat\Side;
use App\Classes\Units\Abstracts\Unit;

abstract class SelectStrategy
{

    private ?Unit $target = null;

    protected function setAsTarget(Unit $unit): void
    {
        $this->target = $unit;
    }

    public function select(Side $side, int $startPosition, int $range, bool $checkBothLines): ?Unit
    {
        $this->target = null;
        $this->reset();
        $front = $side->getFront();
        $back = $side->getBack();
        /** @var ?Unit $unit */
        for ($i = 0; $i <= 2 * $range; $i++) {
            $position = PositionFormula::calc($startPosition, $i);
            $unit = $front->get($position);
            $this->verify($unit);
            if ($checkBothLines || $unit === null || $unit->isDead()) {
                $unit = $back->get($position);
                $this->verify($unit);
            }
        }
        return $this->target;
    }

    protected abstract function reset(): void;

    private function inRange(int $position, int $startPosition, int $range): bool
    {
        return $position >= $startPosition - $range && $position <= $startPosition + $range;
    }

    private function verify(?Unit $unit): void
    {
        if ($unit !== null && $unit->isAlive()) {
            $this->checkUnit($unit);
        }
    }

    protected abstract function checkUnit(Unit $unit): void;
    
}