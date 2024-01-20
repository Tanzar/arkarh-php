<?php

namespace App\Classes\Units\Abstracts;

use App\Classes\Abilities\Attack\AttackBuilder;
use App\Classes\Abilities\Shared\AbilityBuilder;
use App\Classes\Shared\Utility\IdGenerator;
use App\Classes\Tag\Unit\Tag;
use Closure;
use Illuminate\Support\Collection;

class UnitBuilder
{
    private int $id;

    //Unit display name
    private string $name;

    //Unit icon displayed in game
    private string $icon;

    private bool $prefersFront = true;

    private Collection $stats;

    private Collection $abilities;

    private Collection $tags;

    public function __construct(string $name, string $icon)
    {
        $this->id = IdGenerator::get();
        $this->stats = collect();
        $this->name = $name;
        $this->icon = $icon;
        $this->abilities = collect();
        $this->tags = collect();
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

    public function threat(int $value): UnitBuilder
    {
        $this->stats->put('threat', $value);
        return $this;
    }

    public function addAttack(string $sctiprName, Closure $function): UnitBuilder
    {
        $builder = new AttackBuilder();
        $function($builder);
        $this->abilities->put($sctiprName, $builder);
        return $this;
    }

    public function tag(Tag $tag): UnitBuilder
    {
        $this->tags->push($tag);
        return $this;
    }

    public function prefersFront(): UnitBuilder
    {
        $this->prefersFront = true;
        return $this;
    }

    public function prefersBack(): UnitBuilder
    {
        $this->prefersFront = false;
        return $this;
    }

    public function build(): Unit
    {
        $unit = new Unit(
            $this->id,
            $this->name,
            $this->icon
        );
        $this->applyOffensive($unit);
        $this->applyDefensive($unit);
        $this->applyStatus($unit);
        $this->addAbilities($unit);
        $this->addTags($unit);
        $unit->prefersFront($this->prefersFront);
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
        if ($this->stats->has('threat')) {
            $unit->setThreat($this->stats->get('threat'));
        }
    }

    private function addAbilities(Unit $unit): void
    {
        /** @var AbilityBuilder $builder */
        foreach ($this->abilities as $scriptName => $builder) {
            $ability = $builder->build($unit);
            $unit->addAbility($scriptName, $ability);
        }
    }

    private function addTags(Unit $unit): void
    {
        /** @var Tag $tag */
        foreach ($this->tags as $tag) {
            $unit->addTag($tag);
        }
    }
}