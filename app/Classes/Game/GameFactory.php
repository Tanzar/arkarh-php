<?php

namespace App\Classes\Game;

use App\Classes\Combat\ArmyPattern;
use App\Classes\Combat\ArmyPatterns;
use App\Classes\Combat\Battlefield;
use App\Classes\Combat\Side;
use App\Classes\Factions\Abstracts\Faction;
use App\Classes\Game\Exceptions\FactionNotFoundException;
use App\Classes\Game\Exceptions\UndefinedScriptException;
use App\Classes\Units\Abstracts\Unit;
use Illuminate\Support\Collection;

abstract class GameFactory implements FactionsInterface
{
    private Collection $factions;
    
    protected function __construct() {
        $this->factions = collect();
        $this->factions($this);
    }

    protected abstract function factions(FactionsInterface $factions): void;

    public function formBattlefield(Collection $attackers, Collection $defenders): Battlefield
    {
        $attackerSide = $this->formSide($attackers, true);
        $defenderSide = $this->formSide($defenders, false);
        return new Battlefield($attackerSide, $defenderSide);
    }

    public function formSide(Collection $armiesJsons, bool $attackers): Side
    {
        $patterns = new ArmyPatterns();
        foreach ($armiesJsons as $armiesJson) {
            $pattern = $this->formArmyPattern($armiesJson, $attackers);
            $patterns->addPattern($pattern);
        }
        return new Side($patterns);
    }

    public function formArmyPattern(string $armyJson, bool $attackers): ArmyPattern
    {
        $army = json_decode($armyJson, true);
        $front = $this->parseLine($army, 'front', $attackers);
        $back = $this->parseLine($army, 'back', $attackers);
        $reserve = $this->parseLine($army, 'reserve', $attackers);
        return new ArmyPattern($reserve, $front, $back);
    }

    private function parseLine(array $army, string $line, bool $attackers): array
    {
        $array = [];
        if (isset($army[$line])) {
            foreach ($army[$line] as $position => $unitScript) {
                $unit = $this->formUnit($unitScript);
                if ($attackers) {
                    $unit->setAttacker();
                } else {
                    $unit->setDefender();
                }
                $array[$position] = $unit;
            }
        }
        return $array;
    }

    private function formUnit(string $script): Unit
    {
        $scripts = explode(".", $script);
        $faction = $this->getFaction($scripts[0]);
        if ($scripts[1] === 'unit') {
            return $faction->getUnit($scripts[2]);
        }
        throw new UndefinedScriptException('unit', $script);
    }

    public function getFaction(string $script): Faction
    {
        $faction = $this->factions->get($script);
        if ($faction === null) {
            throw new FactionNotFoundException($script);
        }
        return $faction;
    }

    public function add(string $scriptName, Faction $faction): void
    {
        if ($this->factions->doesntContain($scriptName)) {
            $this->factions->put($scriptName, $faction);
        }
    }
}