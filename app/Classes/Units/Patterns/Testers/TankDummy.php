<?php

namespace App\Classes\Units\Patterns\Testers;

use App\Classes\Abilities\Attack\AttackBuilder;
use App\Classes\Abilities\Buff\BuffBuilder;
use App\Classes\Modifiers\Category;
use App\Classes\Modifiers\ModifierBuilder;
use App\Classes\Tag\Unit\Tags\Vampire;
use App\Classes\Units\Abstracts\UnitBuilder;
use App\Classes\Units\Abstracts\UnitPattern;

class TankDummy extends UnitPattern
{

    protected function __construct() { }
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
            ->health(300)
            ->addAttack('strike', function(AttackBuilder $attack) {
                $attack->name('Dummy slap')->damage(5);
            })
            ->addBuff('fortify', function(BuffBuilder $buff) {
                $buff
                    ->name('Fortify')
                    ->targetSelf()
                    ->applies('Fortification', Category::Armor, function(ModifierBuilder $modifier) {
                        $modifier->stackValue(10)->maxStacks(5)->stacksChange(1);
                    });
            })
            ->tag(new Vampire());
    }

}