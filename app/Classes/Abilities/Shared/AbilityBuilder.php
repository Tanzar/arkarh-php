<?php

namespace App\Classes\Abilities\Shared;
use App\Classes\Units\Abstracts\Unit;

abstract class AbilityBuilder
{
    private ?int $charges;
    
    private ?int $cooldown;
    private ?int $defaultCooldown;

    public function build(Unit $unit): Ability
    {
        $ability = $this->createAbility($unit);
        $this->setupCooldown($ability);
        $this->setupCharges($ability);
        return $ability;
    }

    private function setupCooldown(Ability $ability): void
    {
        if ($this->defaultCooldown !== null && $this->cooldown !== null) {
            $ability->setCooldown($this->defaultCooldown, $this->cooldown);
        } elseif ($this->defaultCooldown !== null) {
            $ability->setCooldown($this->defaultCooldown);
        }
    }

    private function setupCharges(Ability $ability): void
    {
        if ($this->charges !== null) {
            $ability->setCharges($this->charges);
        } else {
            $ability->setUnlimitedCharges();
        }
    }

    protected abstract function createAbility(Unit $unit): Ability;

    /**
     * Set the value of chages
     *
     * @return  self
     */ 
    public function charges(int $charges): AbilityBuilder
    {
        if ($charges > 0) {
            $this->charges = $charges;
        }
        return $this;
    }

    /**
     * Set the value of cooldown
     *
     * @return  self
     */ 
    public function startCooldown(int $cooldown): AbilityBuilder
    {
        if ($cooldown >= 0) {
            $this->cooldown = $cooldown;
        }
        return $this;
    }

    /**
     * Set the value of defaultCooldown
     *
     * @return  self
     */ 
    public function cooldown(int $defaultCooldown): AbilityBuilder
    {
        if ($defaultCooldown >= 0) {
            $this->defaultCooldown = $defaultCooldown;
        }
        return $this;
    }
}