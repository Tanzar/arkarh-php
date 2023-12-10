<?php

namespace App\Classes\Units\Patterns\Testers;

use App\Classes\Abilities\Attack\AttackBuilder;
use App\Classes\Units\Abstracts\UnitBuilder;
use App\Classes\Units\Abstracts\UnitPattern;

class ArcherDummy extends UnitPattern
{

    protected function setScriptName(): string
    {
        return 'archerDummy';
    }

    protected function setName(): string
    {
        return 'Archer Dummy';
    }

    protected function setIcon(): string
    {
        return asset('storage/units/dummies/dummy_archer.png');
    }

    protected function pattern(UnitBuilder $builder): void {

        $attack = new AttackBuilder();

        $attack->damage(20)->piercing()->range(10);

        $builder
            ->ability($attack)
            ->prefersBack()
            ->speed(6)
            ->health(50);
    }

}