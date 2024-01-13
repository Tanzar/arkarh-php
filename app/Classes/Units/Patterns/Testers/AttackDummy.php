<?php

namespace App\Classes\Units\Patterns\Testers;

use App\Classes\Abilities\Attack\AttackBuilder;
use App\Classes\Units\Abstracts\UnitBuilder;
use App\Classes\Units\Abstracts\UnitPattern;

class AttackDummy extends UnitPattern
{

    protected function setName(): string { return 'Attack Dummy'; }

    protected function setIcon(): string
    { 
        return asset('storage/units/dummies/dummy_dps.png');
    }

    protected function pattern(UnitBuilder $builder): void 
    {
        $builder
            ->speed(5)
            ->health(100)
            ->addAttack(function(AttackBuilder $attack) {
                $attack->name('Melee strike')->damage(10);
            });
    }

}