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

    public static function testBattle(): BattleState
    {
        $string = '{
            "front": {
                "10": "testers.unit.attackDummy"
            },
            "back": {},
            "reserves": {}
        }';
        $jsons = new Collection();
        $jsons->add($string);
        $attackers = GameConfig::getInstance()->formSide($jsons, true);
        $string = '{
            "front": {
                "9": "testers.unit.attackDummy",
                "10": "testers.unit.attackDummy",
                "11": "testers.unit.attackDummy"
            },
            "back": {},
            "reserves": {}
        }';
        $jsons = new Collection();
        $jsons->add($string);
        $defenders = GameConfig::getInstance()->formSide($jsons, false);
        return Battlefield::getInstance($attackers, $defenders)->startBattle();
    }

}