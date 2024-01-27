<?php

namespace App\Classes\Units\Patterns\Testers;

use App\Classes\Abilities\Attack\AttackBuilder;
use App\Classes\Abilities\Heal\HealBuilder;
use App\Classes\Modifiers\Category;
use App\Classes\Modifiers\ModifierBuilder;
use App\Classes\Shared\Types\School;
use App\Classes\Tag\Unit\Tags\Vampire;
use App\Classes\Units\Abstracts\UnitBuilder;
use App\Classes\Units\Abstracts\UnitPattern;

class MedicDummy extends UnitPattern
{

    protected function __construct() { }
    protected function setName(): string
    {
        return 'Medic Dummy';
    }

    protected function setIcon(): string
    {
        return asset('storage/units/dummies/dummy_medic.png');
    }

    protected function pattern(UnitBuilder $builder): void {

        $builder
            ->armor(50)
            ->speed(3)
            ->health(100)
            ->prefersBack()
            ->addHeal('heal', function(HealBuilder $heal) {
                $heal
                    ->name('Medic word: Shout')
                    ->value(10)
                    ->cooldown(3)
                    ->targetSingle(5)
                    ->applies(
                        'Mending', 
                        Category::HealOverTime, 
                        function(ModifierBuilder $modifier) {
                        $modifier->school(School::Light)->stackValue(10);
                    });
            });
    }

}