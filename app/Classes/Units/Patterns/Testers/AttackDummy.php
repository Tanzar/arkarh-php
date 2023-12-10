<?php

namespace App\Classes\Units\Patterns\Testers;

use App\Classes\Abilities\Attack\AttackBuilder;
use App\Classes\Tag\Unit\Tags\Vampire;
use App\Classes\Units\Abstracts\UnitBuilder;
use App\Classes\Units\Abstracts\UnitPattern;

class AttackDummy extends UnitPattern
{

    protected function setScriptName(): string { return 'AttackDummy'; }

    protected function setName(): string { return 'Attack Dummy'; }

    protected function setIcon(): string
    { 
        return asset('storage/units/dummies/dummy_fighter.png');
    }

    protected function pattern(UnitBuilder $builder): void 
    {
        $attack = new AttackBuilder();

        $attack->damage(20);

        $builder
            ->ability($attack)
            ->health(500)
            ->tag(new Vampire())
            ;
    }

}