<?php

namespace App\Classes\Game;

use App\Classes\Combat\ArmyPattern;
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

    public function formArmyPattern(string $armyJson): ArmyPattern
    {
        $army = json_decode($armyJson, true);
        $front = $this->parseLine($army, 'front');
        $back = $this->parseLine($army, 'back');
        $reserve = $this->parseLine($army, 'reserve');
        return new ArmyPattern($reserve, $front, $back);
    }

    private function parseLine(array $army, string $line): array
    {
        $array = [];
        if (isset($army[$line])) {
            foreach ($army[$line] as $position => $unitScript) {
                $array[$position] = $this->formUnit($unitScript);
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

    protected abstract function factions(FactionsInterface $factions): void;

    public function add(string $scriptName, Faction $faction): void
    {
        if ($this->factions->doesntContain($scriptName)) {
            $this->factions->put($scriptName, $faction);
        }
    }
}