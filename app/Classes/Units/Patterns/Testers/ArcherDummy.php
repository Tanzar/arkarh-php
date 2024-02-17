<?php

namespace App\Classes\Units\Patterns\Testers;

use App\Classes\Abilities\Attack\AttackBuilder;
use App\Classes\Abilities\Debuff\DebuffBuilder;
use App\Classes\Modifiers\Category;
use App\Classes\Modifiers\ModifierBuilder;
use App\Classes\Shared\Types\School;
use App\Classes\Units\Abstracts\UnitBuilder;
use App\Classes\Units\Abstracts\UnitPattern;

class ArcherDummy extends UnitPattern
{

    protected function __construct() { }

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
            ->addAttack('shoot', function(AttackBuilder $attack) {
                $attack
                    ->name('Range attack')
                    ->damage(20)
                    ->piercing()
                    ->singleTargetByThreat(10)
                    ->cooldown(1);
            })
            ->addDebuff('mark', function(DebuffBuilder $debuff) {
                $debuff
                    ->name("Hunter mark")
                    ->targetSingleHighestThreat(20)
                    ->applies('Mark', Category::DamageTakenMultiplier, function(ModifierBuilder $modifier) {
                        $modifier
                            ->stackValue(0.1)
                            ->maxStacks(10)
                            ->school(School::Physical)
                            ->uniquePerUnitType();
                    });
            });
    }

}