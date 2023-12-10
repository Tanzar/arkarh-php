<?php

namespace App\Classes\Units\Patterns\Testers;

use App\Classes\Abilities\Attack\AttackBuilder;
use App\Classes\Units\Abstracts\UnitBuilder;
use App\Classes\Units\Abstracts\UnitPattern;

class AttackDummy extends UnitPattern
{

    protected function setScriptName(): string { return 'AttackDummy'; }

    protected function setName(): string { return 'Attack Dummy'; }

    protected function setIcon(): string
    { 
        return asset('storage/units/dummies/dummy_dps.png');
    }

    protected function pattern(UnitBuilder $builder): void 
    {
        $attack = new AttackBuilder();

        $attack->damage(10);

        $builder
            ->ability($attack)
            ->speed(5)
            ->health(100);
    }

}