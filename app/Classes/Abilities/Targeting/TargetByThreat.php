<?php

namespace App\Classes\Abilities\Targeting;
use App\Classes\Abilities\Targeting\Abstracts\TargetSelectionStartegy;
use App\Classes\Combat\Side;
use App\Classes\Units\Abstracts\Unit;
use App\Classes\Units\Support\UnitsContainer;

class TargetByThreat extends TargetSelectionStartegy
{
    private int $area = 0;

    private bool $bothLines = false;

    private int $mainTargetPosition = -1;

    private int $mainTargetThreat = 0;

    private ?Side $side = null;

    public function __construct(int $area = 0, bool $bothLines = false)
    {
        if ($area >= 0) {
            $this->area = $area;
        }
        $this->bothLines = $bothLines;
    }

    protected function preSelect(): void
    {
        $this->mainTargetPosition = 0;
        $this->mainTargetThreat = 0;
    }

    protected function checkPosition(Side $side, int $position, UnitsContainer $targets): void
    {
        if ($this->side === null) {
            $this->side = $side;
        }
        $front = $side->getFrontUnit($position);
        $this->checkUnit($front, $position);
        if ($front === null || $this->bothLines) {
            $back = $side->getBackUnit($position);
            $this->checkUnit($back, $position);
        }
        
    }

    private function checkUnit(?Unit $unit, int $position): void
    {
        if ($unit !== null) {
            $threat = $unit->getThreat();
            if ($threat > $this->mainTargetThreat) {
                $this->mainTargetPosition = $position;
                $this->mainTargetThreat = $threat;
            }
        }
    }

    protected function postSelect(UnitsContainer $tragets): void
    {
        if ($this->mainTargetPosition > -1) {
            for ($i = 0; $i <= $this->area * 2; $i++) {
                $position = $this->calcPosition($this->mainTargetPosition, $i);
                $front = $this->side->getFrontUnit($position);
                $tragets->addUnit($front);
                if ($front === null || $this->bothLines) {
                    $back = $this->side->getBackUnit($position);
                    $tragets->addUnit($back);
                }
            }
        }
    }

}