<?php

namespace App\Classes\Factions\Testers;
use App\Classes\Factions\Abstracts\Faction;
use App\Classes\Factions\Abstracts\FactionUnitsInterface;
use App\Classes\Units\Patterns\Testers\ArcherDummy;
use App\Classes\Units\Patterns\Testers\AttackDummy;
use App\Classes\Units\Patterns\Testers\MedicDummy;
use App\Classes\Units\Patterns\Testers\TankDummy;
use App\Classes\Units\Patterns\Testers\WizardDummy;

class Testers extends Faction
{

    protected function units(FactionUnitsInterface $units): void
    {
        $units->add('fighter', AttackDummy::getInstance());
        $units->add('tank', TankDummy::getInstance());
        $units->add('archer', ArcherDummy::getInstance());
        $units->add('wizard', WizardDummy::getInstance());
        $units->add('medic', MedicDummy::getInstance());
    }

    public function canPlayerSelect(): bool
    {
        return true;
    }

}