<?php

namespace App\Services;

use App\Classes\Combat\ArmyPattern;
use App\Classes\Combat\ArmyPatterns;
use App\Classes\Combat\Battlefield;
use App\Classes\Combat\BattleState;
use App\Classes\Combat\Side;
use App\Classes\Game\GameConfig;
use Illuminate\Support\Collection;

class CombatService
{

    public static function testBattle(): array
    {
        $string = '{
            "front": {
                "5": "testers.unit.fighter",
                "6": "testers.unit.fighter",
                "7": "testers.unit.fighter",
                "8": "testers.unit.fighter",
                "9": "testers.unit.fighter"
            },
            "back": {
                "5": "testers.unit.archer",
                "6": "testers.unit.archer",
                "7": "testers.unit.archer",
                "8": "testers.unit.archer",
                "9": "testers.unit.archer"
            },
            "reserves": {}
        }';
        $jsons = new Collection();
        $jsons->add($string);
        $attackers = GameConfig::getInstance()->formSide($jsons, true);
        $string = '{
            "front": {
                "6": "testers.unit.fighter",
                "7": "testers.unit.tank",
                "8": "testers.unit.fighter"
            },
            "back": {
                "7": "testers.unit.wizard"
            },
            "reserves": {}
        }';
        $jsons = new Collection();
        $jsons->add($string);
        $defenders = GameConfig::getInstance()->formSide($jsons, false);
        $log = Battlefield::getInstance($attackers, $defenders)->startBattle();
        return $log->toArray();
    }

}