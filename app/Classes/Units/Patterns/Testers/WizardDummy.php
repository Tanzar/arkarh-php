<?php

namespace App\Classes\Units\Patterns\Testers;

use App\Classes\Abilities\Attack\AttackBuilder;
use App\Classes\Modifiers\Category;
use App\Classes\Modifiers\ModifierBuilder;
use App\Classes\Shared\Types\School;
use App\Classes\Units\Abstracts\UnitBuilder;
use App\Classes\Units\Abstracts\UnitPattern;

class WizardDummy extends UnitPattern
{

    protected function __construct() { }

    protected function setName(): string
    {
        return 'Wizard Dummy';
    }

    protected function setIcon(): string
    {
        return asset('storage/units/dummies/dummy_wizard.png');
    }

    protected function pattern(UnitBuilder $builder): void {
        $builder
            ->prefersBack()
            ->speed(4)
            ->health(50)
            ->addAttack('bombard', function(AttackBuilder $attack) {
            $attack->name('Arcane Bombardment')
                ->initialCooldown(2)
                ->damage(20)
                ->piercing()
                ->school(School::Arcane)
                ->areaByThreat(5, 2)
                ->applies(
                    'Arcane Weakness', 
                    Category::DamageTakenMultiplier, 
                    function(ModifierBuilder $modifier) {
                        $modifier->school(School::Arcane)->stackValue(0.5)->maxStacks(2);
                    });
        });
            
    }

}