<?php

namespace App\Classes\Abilities\Attack;

use App\Classes\Abilities\Targeting\Enemies\Single;
use App\Classes\Abilities\Targeting\Primary\HighestThreat;
use App\Classes\Abilities\Targeting\Primary\LowestHealth;
use App\Classes\Abilities\Shared\Ability;
use App\Classes\Abilities\Shared\AbilityBuilder;
use App\Classes\Abilities\Targeting\Abstracts\Targeting;
use App\Classes\Abilities\Targeting\Enemies\Area;
use App\Classes\Abilities\Targeting\Enemies\Cleave;
use App\Classes\Modifiers\Category;
use App\Classes\Modifiers\Modifier;
use App\Classes\Shared\Types\School;
use App\Classes\Units\Abstracts\Unit;
use Closure;
use Illuminate\Support\Collection;

class AttackBuilder implements AbilityBuilder
{
    private string $name = 'attack';

    private int $charges = Ability::DEFAULT_CHARGES;
    
    private int $cooldown = Ability::DEFAULT_COOLDOWN;

    private int $initialCooldown = Ability::DEFAULT_COOLDOWN;

    private int $damage = 1;

    private School $school = School::Physical;

    private bool $piercing = false;

    private ?Targeting $targetSelection;

    private Collection $modifiers;

    public function __construct()
    {
        $this->modifiers = new Collection();
    }

    public function name(string $name): AttackBuilder
    {
        $this->name = $name;
        return $this;
    }

    public function charges(int $charges): AttackBuilder
    {
        if ($charges > 0) {
            $this->charges = $charges;
        }
        return $this;
    }

    public function unlimitedCharges(): AttackBuilder
    {
        $this->charges = -1;
        return $this;
    }

    public function initialCooldown(int $cooldown): AttackBuilder
    {
        if ($cooldown >= 0) {
            $this->initialCooldown = $cooldown;
        }
        return $this;
    }

    public function cooldown(int $cooldown): AttackBuilder
    {
        if ($cooldown >= 0) {
            $this->cooldown = $cooldown;
        }
        return $this;
    }

    public function damage(int $damage): AttackBuilder
    {
        if ($this->damage > 0) {
            $this->damage = $damage;
        }
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

    public function applies(string $name, Category $category, Closure $function): AttackBuilder
    {
        $modifier = new Modifier($name, $category);
        $function($modifier);
        $this->modifiers->add($modifier);
        return $this;
    }

    public function singleTargetByThreat(int $range): AttackBuilder
    {
        $priority = new HighestThreat();
        $this->targetSelection = new Single($priority, $range);
        return $this;
    }

    public function singleTargetByLowestHealth(int $range): AttackBuilder
    {
        $priority = new LowestHealth();
        $this->targetSelection = new Single($priority, $range);
        return $this;
    }

    public function cleaveByThreat(int $range, int $radius): AttackBuilder
    {
        $priority = new HighestThreat();
        $this->targetSelection = new Cleave($priority, $range, $radius);
        return $this;
    }

    public function cleaveByLowestHealth(int $range, int $radius): AttackBuilder
    {
        $priority = new LowestHealth();
        $this->targetSelection = new Cleave($priority, $range, $radius);
        return $this;
    }

    public function areaByThreat(int $range, int $radius): AttackBuilder
    {
        $priority = new HighestThreat();
        $this->targetSelection = new Area($priority, $range, $radius);
        return $this;
    }

    public function areaByLowestHealth(int $range, int $radius): AttackBuilder
    {
        $priority = new LowestHealth();
        $this->targetSelection = new Area($priority, $range, $radius);
        return $this;
    }

    public function build(Unit $unit): Ability {
        $attack = new Attack($this->name, $unit);
        $attack->setCharges($this->charges);
        $attack->setCooldown($this->cooldown, $this->initialCooldown);
        $attack->setDamage($this->damage);
        if ($this->piercing) {
            $attack->setPiercing();
        } else {
            $attack->unsetPiercing();
        }
        $attack->setSchool($this->school);
        if (isset($this->targetSelection)) {
            $attack->setTargeting($this->targetSelection);
        }
        foreach ($this->modifiers as $builder) {
            $attack->addModifier($builder);
        }
        return $attack;
     }

}