<?php

namespace App\Classes\Abilities\Shared;

use App\Classes\Combat\Battlefield;
use App\Classes\Combat\CombatLog;
use App\Classes\Units\Abstracts\Unit;

abstract class Ability
{
    public const DEFAULT_CHARGES = -1;
    public const DEFAULT_COOLDOWN = 0;

    private string $name = 'ability';

    private int $charges = self::DEFAULT_CHARGES;

    private int $cooldown = self::DEFAULT_COOLDOWN;
    private int $defaultCooldown = self::DEFAULT_COOLDOWN;

    private Trigger $trigger = Trigger::Action;

    private Unit $source;

    private int $logStage = 0;

    public function __construct(string $name, Unit $source)
    {
        $this->name = $name;
        $this->source = $source;
    }

    /**
     * Set the value of charges
     */ 
    public function setCharges(int $charges): void
    {
        if ($charges > 0) {
            $this->charges = $charges;
        } else {
            $this->charges = -1;
        }
    }

    /**
     * Set the value of cooldown
     */ 
    public function setCooldown(int $cooldown, int $startCooldown = 0): void
    {
        if ($cooldown >= 0) {
            $this->defaultCooldown = $cooldown;
        }
        if ($startCooldown >= 0) {
            $this->cooldown = $startCooldown;
        }
    }

    /**
     * Get the value of trigger
     */ 
    public function getTrigger(): Trigger
    {
        return $this->trigger;
    }

    /**
     * Set the value of trigger
     */ 
    protected function setTrigger(Trigger $trigger): void
    {
        $this->trigger = $trigger;
    }

    public function getSource(): Unit
    {
        return $this->source;
    }

    public function act(): void
    {
        if ($this->isAvailable()) {
            $this->logStage = CombatLog::getInstance()->nextStage();
            $text = $this->actionLog();
            CombatLog::getInstance()->addAbility($this->source, $text);
            $battlefield = Battlefield::getInstance();
            $succeeded = $this->action($battlefield);
            if ($succeeded) {
                $this->useCharge();
                $this->incurCooldown();
            }
        } elseif ($this->cooldown > 0) {
            $this->cooldown--;
        }
    }

    private function isAvailable(): bool
    {
        return ($this->charges !== 0) && $this->cooldown === 0;
    }

    protected function actionLog(): string
    {
        return $this->source->getName() . ' uses ' . $this->name;
    }

    protected abstract function action(Battlefield $battlefield): bool;

    private function useCharge(): void
    {
        if ($this->charges > 0) {
            $this->charges--;
        }
    }

    public function incurCooldown(): void
    {
        $this->cooldown = $this->defaultCooldown;
    }

    protected function logUnitStage(Unit $unit, string $text, bool $reserve = false, bool $graveyard = false): void
    {
        CombatLog::getInstance()->addState($unit, $text, $reserve, $graveyard, $this->logStage);
    }
}