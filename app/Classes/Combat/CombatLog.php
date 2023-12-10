<?php

namespace App\Classes\Combat;

use App\Classes\Units\Abstracts\Unit;

class CombatLog
{
    private static ?self $instance = null;

    private int $stage = 1;

    private array $logs = [];

    private function __construct()
    {
        $this->logs = [
            1 => []
        ];
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function nextStage(): int
    {
        $this->stage++;
        $this->logs[$this->stage] = [];
        return $this->stage;
    }

    public function addState(Unit $unit, string $text, bool $reserve = false, bool $graveyard = false, int $stage = 0): void
    {
        if ($stage <= 0) {
            $stage = $this->stage;
        }
        $this->logs[$stage][] = [
            'type' => 'state',
            'position' => $unit->getPosition(),
            'line' => $unit->prefersFront() ? 'front' : 'back',
            'side' => $unit->isAttacker() ? 'attackers' : 'defenders',
            'inReserve' => $reserve,
            'inGrave' => $graveyard,
            'health' => $unit->getHealth(),
            'morale' => ($unit->getMorale() / $unit->getMaxMorale()) * 100,
            'alive' => $unit->isAlive(),
            'icon' => $unit->getIcon(),
            'name' => $unit->getName(),
            'text' => $text
        ];
    }

    public function addAbility(Unit $unit, string $text, int $stage = 0): void
    {
        if ($stage <= 0) {
            $stage = $this->stage;
        }
        $this->logs[$stage][] = [
            'type' => 'ability',
            'position' => $unit->getPosition(),
            'line' => $unit->prefersFront() ? 'front' : 'back',
            'side' => $unit->isAttacker() ? 'attackers' : 'defenders',
            'text' => $text
        ];
    }

    public function addTick(int $tick, int $stage = 0): void
    {
        if ($stage <= 0) {
            $stage = $this->stage;
        }
        $this->logs[$stage][] = [
            'type' => 'tick',
            'tick' => $tick,
            'text' => "Start tick nr. $tick"
        ];
    }

    public function toArray(): array
    {
        return $this->logs;
    }
}