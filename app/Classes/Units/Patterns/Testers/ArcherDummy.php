<?php

namespace App\Classes\Units\Patterns\Testers;

use App\Classes\Abilities\Attack\AttackBuilder;
use App\Classes\Modifiers\Base\Category;
use App\Classes\Modifiers\Base\ModifierBuilder;
use App\Classes\Shared\Types\School;
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
                    ->cooldown(1)
                    ->applies(
                        'Knee shot', 
                        Category::DamageTakenMultiplier, 
                        function(ModifierBuilder $modifier) {
                            $modifier
                                ->school(School::Physical)
                                ->maxStacks(2)
                                ->stackValue(0.5)
                                ->stacksChange(1)
                                ->uniquePerUnitType();
                    });
            });
    }

}