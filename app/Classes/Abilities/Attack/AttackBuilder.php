<?php

namespace App\Classes\Abilities\Attack;

use App\Classes\Abilities\Targeting\Primary\HighestThreat;
use App\Classes\Abilities\Targeting\Primary\LowestHealth;
use App\Classes\Abilities\Targeting\Primary\SelectStrategy;
use App\Classes\Abilities\Shared\Ability;
use App\Classes\Abilities\Shared\AbilityBuilder;
use App\Classes\Modifiers\ModifierBuilder;
use App\Classes\Shared\Types\School;
use App\Classes\Units\Abstracts\Unit;
use Illuminate\Support\Collection;

class AttackBuilder implements AbilityBuilder
{
    private string $name = 'attack';

    private int $charges = Ability::DEFAULT_CHARGES;
    
    private int $cooldown = Ability::DEFAULT_COOLDOWN;

    private int $initialCooldown = Ability::DEFAULT_COOLDOWN;

    private int $range = 1;

    private int $damage = 1;

    private int $area = 0;

    private bool $bothLines = false;

    private School $school = School::Physical;

    private bool $piercing = false;

    private SelectStrategy $targetSelection;

    private Collection $modifiers;

    public function __construct()
    {
        $this->modifiers = new Collection();
        $this->targetSelection = new HighestThreat();
    }

    public function name(string $name): AttackBuilder
    {
        $this->name = $name;
        return $this;
    }

    public function charges(int $charges): AttackBuilder
    {
        $this->charges = $charges;
        return $this;
    }

    public function initialCooldown(int $cooldown): AttackBuilder
    {
        $this->initialCooldown = $cooldown;
        return $this;
    }

    public function cooldown(int $cooldown): AttackBuilder
    {
        $this->cooldown = $cooldown;
        return $this;
    }

    public function range(int $range): AttackBuilder
    {
        $this->range = $range;
        return $this;
    }

    public function damage(int $damage): AttackBuilder
    {
        $this->damage = $damage;
        return $this;
    }

    public function area(int $area): AttackBuilder
    {
        $this->area = $area;
        return $this;
    }

    public function strikeBothLines(): AttackBuilder
    {
        $this->bothLines = true;
        return $this;
    }

    public function strikeSingleLine(): AttackBuilder
    {
        $this->bothLines = false;
        return $this;
    }

    public function school(School $school): AttackBuilder
    {
        $this->school = $school;
        return $this;
    }

    public function piercing(): AttackBuilder
    {
        $this->piercing = true;
        return $this;
    }

    public function notPiercing(): AttackBuilder
    {
        $this->piercing = false;
        return $this;
    }

    public function applies(ModifierBuilder $builder): AttackBuilder
    {
        $this->modifiers->add($builder);
        return $this;
    }

    public function targetHighestThreat(): AttackBuilder
    {
        $this->targetSelection = new HighestThreat();
        return $this;
    }

    public function targetLowestHealth(): AttackBuilder
    {
        $this->targetSelection = new LowestHealth();
        return $this;
    }

    public function build(Unit $unit): Ability {
        $attack = new Attack($this->name, $unit);
        $attack->setCharges($this->charges);
        $attack->setCooldown($this->cooldown, $this->initialCooldown);
        $attack->setRange($this->range);
        $attack->setDamage($this->damage);
        $attack->setArea($this->area);
        if ($this->piercing) {
            $attack->setPiercing();
        } else {
            $attack->unsetPiercing();
        }
        if ($this->bothLines) {
            $attack->setStrikeBothLines();
        } else {
            $attack->setStrikeSingleLine();
        }
        $attack->setSchool($this->school);
        $attack->setTargetSelection($this->targetSelection);
        foreach ($this->modifiers as $builder) {
            $attack->addModifier($builder);
        }
        return $attack;
     }

}