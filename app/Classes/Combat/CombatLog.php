<?php

namespace App\Classes\Combat;

use App\Classes\Units\Abstracts\Unit;
use Illuminate\Support\Collection;

class CombatLog
{
    private static ?self $instance = null;

    private array $log = [];

    private array $states = [];

    private int $tick = 1;

    private function __construct()
    {

    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function nextTick(): void
    {
        $this->tick++;
    }

    public function saveState(Battlefield $battlefield): void
    {
        $this->states[$this->tick] = [
            'attackers' => $this->saveSideState($battlefield->getAttackers()),
            'defenders' => $this->saveSideState($battlefield->getDefenders())
        ];
    }

    private function saveSideState(Side $side): array
    {
        return [
            'front' => $this->formLine($side->getFront()),
            'back' => $this->formLine($side->getBack()),
            'reserves' => $this->formFromCollection($side->getReserves()),
            'graveyard' => $this->formFromCollection($side->getGraveyard())
        ];
    }

    private function formLine(Line $line): array
    {
        $state = [];
        /** @var ?Unit $unit */
        foreach ($line as $position => $unit) {
            if ($unit !== null) {
                $state[$position] = $this->parseUnitState($unit);
            }
        }
        return $state;
    }

    private function formFromCollection(Collection $units): array
    {
        $state = [];
        foreach ($units as $unit) {
            $state[] = $this->parseUnitState($unit);
        }
        return $state;
    }

    private function parseUnitState(Unit $unit): array
    {
        return [
            'icon' => $unit->getIcon(),
            'health' => $unit->getHealth(),
            'morale' => round(($unit->getMorale() / $unit->getMaxMorale()) * 100)
        ];
    }

    public function saveAction(string $actionType, Unit $source,  array $options): void
    {
        if (!isset($this->log[$this->tick])) {
            $this->log[$this->tick] = [];
        }
        $action = [];
        foreach ($options as $option => $value) {
            $action[$option] = $value;
        }
        $action['actionType'] = $actionType;
        $action['source'] = $source->getPosition();
        $action['sourceName'] = _($source->getName());
        $this->log[$this->tick][] = $action;
    }


    public function toArray(): array
    {
        return [
            'ticks' => $this->tick,
            'logs' => $this->log,
            'states' => $this->states
        ];
    }
}