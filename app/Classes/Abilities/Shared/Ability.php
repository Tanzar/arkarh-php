<?php

namespace App\Classes\Abilities\Shared;

use App\Classes\Combat\Battlefield;
use App\Classes\Units\Abstracts\Unit;

abstract class Ability
{
    private int $charges = -1;

    private int $cooldown = 0;
    private int $defaultCooldown = 0;

    private Trigger $trigger = Trigger::Action;

    private Unit $source;

    public function __construct(Unit $source)
    {
        $this->source = $source;
    }

    /**
     * Set the value of charges
     */ 
    public function setCharges(int $charges): void
    {
        if ($charges > 0) {
            $this->charges = $charges;
        }
    }

    public function setUnlimitedCharges(): void
    {
        $this->charges = -1;
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
            $battlefield = Battlefield::getInstance();
            $succeeded = $this->action($battlefield);
            if ($succeeded) {
                $this->useCharge();
                $this->incurCooldown();
            }
        }
    }

    private function isAvailable(): bool
    {
        return ($this->charges !== 0) && $this->cooldown === 0;
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
}