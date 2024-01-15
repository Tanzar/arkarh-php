<?php

namespace App\Classes\Combat;

use App\Classes\Abilities\Shared\Trigger;
use App\Classes\Units\Abstracts\Unit;
use Illuminate\Support\Collection;

class Battlefield
{
    private static ?self $instance = null;

    private Side $attackers;

    private Side $defenders;

    private int $tickLimit = 10000;

    private int $tick = 1;

    private int $moraleDamage = 5;

    private function __construct(Side $attackers, Side $defenders)
    {
        $this->attackers = $attackers;
        $this->defenders = $defenders;
    }

    public static function getInstance(Side $attackers = null, Side $defenders = null): self
    {
        if (self::$instance === null) {
            self::$instance = new self($attackers, $defenders);
        }
        return self::$instance;
    }
    
    public function getOppositeSide(Unit $unit): Side
    {
        if ($unit->isAttacker()) {
            return $this->getDefenders();
        } else {
            return $this->getAttackers();
        }
    }

    public function getFriendlySide(Unit $unit): Side
    {
        if ($unit->isAttacker()) {
            return $this->getAttackers();
        } else {
            return $this->getDefenders();
        }
    }

    public function getAttackers(): Side
    {
        return $this->attackers;
    }

    public function getDefenders(): Side
    {
        return $this->defenders;
    }

    public function getTick(): int
    {
        return $this->tick;
    }

    public function startBattle(): CombatLog //[PH] temporarly returns void later will return combat log
    {
        $this->tick = 1;
        $state = BattleState::Ongoing;
        $this->firstTick();
        while ($state === BattleState::Ongoing && $this->tick <= $this->tickLimit) {
            CombatLog::getInstance()->nextStage();
            CombatLog::getInstance()->addTick($this->tick);
            $state = $this->tick();
            $this->tick++;
        }
        CombatLog::getInstance()->nextStage();
        CombatLog::getInstance()->addTick($this->tick);
        $this->saveState();
        return CombatLog::getInstance();
    }

    private function firstTick(): void
    {
        $this->saveState();
        $fieldedUnits = $this->getUnitsBySpeed();
        /** @var Unit $unit */
        foreach ($fieldedUnits as $unit) {
            $unit->act(Trigger::Entry);
        }
    }

    
    private function tick(): BattleState
    {
        $this->saveState();
        $fieldedUnits = $this->getUnitsBySpeed();
        /** @var Unit $unit */
        foreach ($fieldedUnits as $unit) {
            $unit->act(Trigger::Action);
            $unit->damageMorale($this->moraleDamage);
        }
        $this->refreshSides();

        return $this->determineState();
    }

    private function saveState(): void
    {
        $fieldedUnits = $this->getUnitsBySpeed();
        /** @var Unit $unit */
        foreach ($fieldedUnits as $unit) {
            $name = $unit->getName();
            CombatLog::getInstance()->addState($unit, "$name is ready for battle.");
        }

        $reserves = $this->attackers->getReserves()->merge($this->defenders->getReserves());
        /** @var Unit $unit */
        foreach ($reserves as $unit) {
            $name = $unit->getName();
            CombatLog::getInstance()->addState($unit, "$name awaits in reserves.", true);
        }

        $graves = $this->attackers->getGraveyard()->merge($this->defenders->getGraveyard());
        /** @var Unit $unit */
        foreach ($graves as $unit) {
            $name = $unit->getName();
            CombatLog::getInstance()->addState($unit, "$name lies in grave.", false, true);
        }
    }


    private function getUnitsBySpeed(): Collection
    {
        $defenders = $this->defenders->getUnitsBySpeed();
        $attackers = $this->attackers->getUnitsBySpeed();
        return $defenders->merge($attackers)->sort(function (Unit $unit) {
            return $unit->getSpeed();
        });
    }

    private function refreshSides(): void
    {
        $defenders = $this->defenders->refresh();
        $attackers = $this->attackers->refresh();
        $combined = $defenders->merge($attackers);
        /** @var Unit $unit */
        foreach ($combined as $unit) {
            $unit->act(Trigger::Entry);
        }
    }

    private function determineState(): BattleState
    {
        $defendersCanFight = $this->defenders->canFight();
        $attackersCanFight = $this->attackers->canFight();
        if ($attackersCanFight && $defendersCanFight) {
            return BattleState::Ongoing;
        } elseif ($attackersCanFight) {
            return BattleState::AttackersVictory;
        } elseif ($defendersCanFight) {
            return BattleState::DefendersVictory;
        }
        return BattleState::Draw;
    }
}