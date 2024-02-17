<?php

namespace App\Classes\Abilities\Shared;

use App\Classes\Shared\Types\School;

trait BasicAbilityBuilder
{
    protected string $name = 'ability';
    private int $charges = -1;
    private int $initialCooldown = 0;
    private int $cooldown = 0;
    private School $school = School::Uncategorized;

    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function charges(int $charges): self
    {
        $this->charges = $charges;
        return $this;
    }

    public function unlimitedCharges(): self
    {
        $this->charges = -1;
        return $this;
    }

    public function initialCooldown(int $cooldown): self
    {
        $this->initialCooldown = $cooldown;
        return $this;
    }

    public function cooldown(int $cooldown): self
    {
        $this->cooldown = $cooldown;
        return $this;
    }

    public function school(School $school): self
    {
        $this->school = $school;
        return $this;
    }

    protected function apply(Ability $ability): void
    {
        $ability->setCharges($this->charges);
        $ability->setCooldown($this->cooldown, $this->initialCooldown);
        $ability->setSchool($this->school);
    }
}