<?php

namespace App\Classes\Abilities\Attack;

use App\Classes\Abilities\Shared\Ability;
use App\Classes\Abilities\Shared\AbilityBuilder;
use App\Classes\Units\Abstracts\Unit;

class AttackBuilder extends AbilityBuilder
{

    protected function createAbility(Unit $unit): Ability {
        $attack = new Attack($unit);

        return $attack;
     }

}