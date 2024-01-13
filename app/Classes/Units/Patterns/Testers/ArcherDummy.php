<?php

namespace App\Classes\Units\Patterns\Testers;

use App\Classes\Abilities\Attack\AttackBuilder;
use App\Classes\Units\Abstracts\UnitBuilder;
use App\Classes\Units\Abstracts\UnitPattern;

class ArcherDummy extends UnitPattern
{

    protected function setName(): string
    {
        return 'Archer Dummy';
    }

    protected function setIcon(): string
    {
        return asset('storage/units/dummies/dummy_archer.png');
    }

    protected function pattern(UnitBuilder $builder): void {

        $builder
            ->prefersBack()
            ->speed(6)
            ->health(50)
            ->addAttack(function(AttackBuilder $attack) {
                $attack
                    ->name('Range attack')
                    ->damage(20)
                    ->piercing()
                    ->range(10)
                    ->cooldown(1);
            });
    }

}