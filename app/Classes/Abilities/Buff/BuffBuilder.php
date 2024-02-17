<?php

namespace App\Classes\Abilities\Buff;

use App\Classes\Abilities\Shared\Ability;
use App\Classes\Abilities\Shared\AbilityBuilder;
use App\Classes\Abilities\Shared\BasicAbilityBuilder;
use App\Classes\Abilities\Shared\Trigger;
use App\Classes\Abilities\Targeting\Abstracts\Targeting;
use App\Classes\Abilities\Targeting\Friendly\Area;
use App\Classes\Abilities\Targeting\Friendly\Count;
use App\Classes\Abilities\Targeting\Friendly\SelfTarget;
use App\Classes\Abilities\Targeting\Friendly\Single;
use App\Classes\Modifiers\Category;
use App\Classes\Modifiers\Modifier;
use App\Classes\Units\Abstracts\Unit;
use Closure;
use Illuminate\Support\Collection;

class BuffBuilder implements AbilityBuilder
{
    use BasicAbilityBuilder;

    private Trigger $trigger = Trigger::Action;

    private ?Targeting $targetSelection;

    private Collection $modifiers;

    public function __construct()
    {
        $this->modifiers = new Collection();
    }

    public function trigger(Trigger $trigger): self
    {
        $this->trigger = $trigger;
        return $this;
    }

    public function targetSelf(): self
    {
        $this->targetSelection = new SelfTarget();
        return $this;
    }

    public function targetByCount(int $range, int $count): self
    {
        $this->targetSelection = new Count($range, $count);
        return $this;
    }

    public function targetSingle(int $range): self
    {
        $this->targetSelection = new Single($range);
        return $this;
    }

    public function targetArea(int $range, int $radius): self
    {
        $this->targetSelection = new Area($range, $radius);
        return $this;
    }
    
    public function applies(string $name, Category $category, Closure $function): self
    {
        $modifier = new Modifier($name, $category);
        $function($modifier);
        $this->modifiers->add($modifier);
        return $this;
    }

    
    public function build(Unit $unit): Ability
    {
        $buff = new Buff($this->name, $unit);
        $this->apply($buff);
        $buff->trigger($this->trigger);
        if (isset($this->targetSelection)) {
            $buff->setTargeting($this->targetSelection);
        }
        foreach ($this->modifiers as $builder) {
            $buff->addModifier($builder);
        }
        return $buff;
    }
}