<?php

namespace App\Classes\Abilities\Attack;
use App\Classes\Abilities\Shared\Ability;
use App\Classes\Abilities\Shared\Trigger;
use App\Classes\Abilities\Targeting\Abstracts\TargetSelectionStartegy;
use App\Classes\Combat\Battlefield;
use App\Classes\Units\Abstracts\Unit;

class Attack extends Ability
{
    private int $range = 1;

    private int $damage = 1;

    private TargetSelectionStartegy $targetSelection;

    public function __construct(Unit $unit)
    {
        parent::__construct($unit);
        $this->setTrigger(Trigger::Action);
    }


    protected function action(Battlefield $battlefield): bool
    {
        
    }

}