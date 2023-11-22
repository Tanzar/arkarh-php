<?php

namespace App\Classes\Factions\Testers;
use App\Classes\Factions\Abstracts\Faction;
use App\Classes\Factions\Abstracts\FactionUnitsInterface;
use App\Classes\Units\Patterns\Testers\AttackDummy;

class Testers extends Faction
{

    protected function units(FactionUnitsInterface $units): void
    {
        $units->add('attackDummy', new AttackDummy());
    }

    protected function canPlayerSelect(): bool { return true; }

}