<?php

namespace App\Classes\Factions\Testers;
use App\Classes\Factions\Abstracts\Faction;
use App\Classes\Factions\Abstracts\FactionUnitsInterface;
use App\Classes\Units\Patterns\Testers\ArcherDummy;
use App\Classes\Units\Patterns\Testers\AttackDummy;
use App\Classes\Units\Patterns\Testers\TankDummy;
use App\Classes\Units\Patterns\Testers\WizardDummy;

class Testers extends Faction
{

    protected function units(FactionUnitsInterface $units): void
    {
        $units->add('fighter', new AttackDummy());
        $units->add('tank', new TankDummy());
        $units->add('archer', new ArcherDummy());
        $units->add('wizard', new WizardDummy());
    }

    protected function canPlayerSelect(): bool { return true; }

}