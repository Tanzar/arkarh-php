<?php

namespace App\Classes\Units\Patterns\Testers;

use App\Classes\Abilities\Attack\AttackBuilder;
use App\Classes\Units\Abstracts\UnitBuilder;
use App\Classes\Units\Abstracts\UnitPattern;

class TankDummy extends UnitPattern
{

    protected function setScriptName(): string
    {
        return 'tankDummy';
    }

    protected function setName(): string
    {
        return 'Tank Dummy';
    }

    protected function setIcon(): string
    {
        return asset('storage/units/dummies/dummy_fighter.png');
    }

    protected function pattern(UnitBuilder $builder): void {

        $builder
            ->armor(50)
            ->threat(10)
            ->speed(1)
            ->health(300);

        $builder->addAttack()->name('Dummy slap')->damage(5);
    }

}