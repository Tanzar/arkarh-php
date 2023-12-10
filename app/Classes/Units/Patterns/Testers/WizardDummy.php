<?php

namespace App\Classes\Units\Patterns\Testers;

use App\Classes\Abilities\Attack\AttackBuilder;
use App\Classes\Shared\Types\School;
use App\Classes\Units\Abstracts\UnitBuilder;
use App\Classes\Units\Abstracts\UnitPattern;

class WizardDummy extends UnitPattern
{

    protected function setScriptName(): string
    {
        return 'wizardDummy';
    }

    protected function setName(): string
    {
        return 'Wizard Dummy';
    }

    protected function setIcon(): string
    {
        return asset('storage/units/dummies/dummy_wizard.png');
    }

    protected function pattern(UnitBuilder $builder): void {

        $attack = new AttackBuilder();

        $attack->damage(20)->piercing()->school(School::Arcane)->area(2)->range(5)->strikeBothLines();

        $builder
            ->ability($attack)
            ->prefersBack()
            ->speed(4)
            ->health(50);
    }

}