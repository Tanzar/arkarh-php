<?php

namespace App\Classes\Units\Abstracts;

use App\Classes\Abilities\Shared\AbilityBuilder;
use Illuminate\Support\Collection;

class UnitBuilder
{
    private string $scriptName;

    //Unit display name
    private string $name;

    //Unit icon displayed in game
    private string $icon;


    private Collection $stats;

    private Collection $abilities;

    public function __construct(string $scriptName, string $name, string $icon)
    {
        $this->stats = collect();
        $this->scriptName = $scriptName;
        $this->name = $name;
        $this->icon = $icon;
        $this->abilities = collect();
    }

    public function attack(int $value): UnitBuilder
    {
        $this->stats->put('attack', $value);
        return $this;
    }
    
    public function defense(int $value): UnitBuilder
    {
        $this->stats->put('defense', $value);
        return $this;
    }

    public function spellPower(int $value): UnitBuilder
    {
        $this->stats->put('spellPower', $value);
        return $this;
    }

    public function health(int $value): UnitBuilder
    {
        $this->stats->put('health', $value);
        return $this;
    }

    public function armor(int $value): UnitBuilder
    {
        $this->stats->put('armor', $value);
        return $this;
    }

    public function ward(int $value): UnitBuilder
    {
        $this->stats->put('ward', $value);
        return $this;
    }

    public function speed(int $value): UnitBuilder
    {
        $this->stats->put('speed', $value);
        return $this;
    }

    public function morale(int $value): UnitBuilder
    {
        $this->stats->put('morale', $value);
        return $this;
    }

    public function ability(AbilityBuilder $builder): UnitBuilder
    {
        $this->abilities->push($builder);
        return $this;
    }

    public function build(): Unit
    {
        $unit = new Unit(
            $this->scriptName,
            $this->name,
            $this->icon
        );
        $this->applyOffensive($unit);
        $this->applyDefensive($unit);
        $this->applyStatus($unit);
        $this->addAbilities($unit);
        return $unit;
    }

    private function applyOffensive(Unit $unit): void
    {
        if ($this->stats->has('attack')) {
            $unit->setAttack($this->stats->get('attack'));
        }
        if ($this->stats->has('spellPower')) {
            $unit->setSpellPower($this->stats->get('spellPower'));
        }
        if ($this->stats->has('speed')) {
            $unit->setSpeed($this->stats->get('speed'));
        }
    }

    private function applyDefensive(Unit $unit): void
    {
        if ($this->stats->has('defense')) {
            $unit->setDefense($this->stats->get('defense'));
        }
        if ($this->stats->has('armor')) {
            $unit->setArmor($this->stats->get('armor'));
        }
        if ($this->stats->has('ward')) {
            $unit->setWard($this->stats->get('ward'));
        }
    }

    private function applyStatus(Unit $unit): void
    {
        if ($this->stats->has('health')) {
            $unit->setHealth($this->stats->get('health'));
        }
        if ($this->stats->has('morale')) {
            $unit->setMorale($this->stats->get('morale'));
        }
    }

    private function addAbilities(Unit $unit): void
    {
        /** @var AbilityBuilder $builder */
        foreach ($this->abilities as $builder) {
            $ability = $builder->build();
            $unit->addAbility($ability);
        }
    }
}