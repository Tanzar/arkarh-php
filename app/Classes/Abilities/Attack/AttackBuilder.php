<?php

namespace App\Classes\Abilities\Attack;

use App\Classes\Abilities\Targeting\Primary\HighestThreat;
use App\Classes\Abilities\Targeting\Primary\LowestHealth;
use App\Classes\Abilities\Targeting\Primary\SelectStrategy;
use App\Classes\Abilities\Shared\Ability;
use App\Classes\Abilities\Shared\AbilityBuilder;
use App\Classes\Shared\Types\School;
use App\Classes\Units\Abstracts\Unit;

class AttackBuilder extends AbilityBuilder
{
    private string $name = 'Attack';

    private int $range = 1;

    private int $damage = 1;

    private int $area = 0;

    private bool $bothLines = false;

    private School $school = School::Physical;

    private bool $piercing = false;

    private SelectStrategy $targetSelection;

    public function __construct()
    {
        $this->targetSelection = new HighestThreat();
    }

    public function name(string $name): AttackBuilder
    {
        $this->name = $name;
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

    protected function createAbility(Unit $unit): Ability {
        $attack = new Attack($this->name, $unit);
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
        return $attack;
     }

}