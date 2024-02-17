<?php

namespace App\Classes\Abilities\Debuff;

use App\Classes\Abilities\Shared\Ability;
use App\Classes\Abilities\Shared\AbilityBuilder;
use App\Classes\Abilities\Shared\BasicAbilityBuilder;
use App\Classes\Abilities\Shared\Trigger;
use App\Classes\Abilities\Targeting\Abstracts\Targeting;
use App\Classes\Abilities\Targeting\Enemies\Area;
use App\Classes\Abilities\Targeting\Enemies\Single;
use App\Classes\Abilities\Targeting\Primary\HighestThreat;
use App\Classes\Modifiers\Category;
use App\Classes\Modifiers\Modifier;
use App\Classes\Units\Abstracts\Unit;
use Closure;
use Illuminate\Support\Collection;

class DebuffBuilder implements AbilityBuilder
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

    public function targetSingleHighestThreat(int $range): self
    {
        $this->targetSelection = new Single(new HighestThreat(), $range);
        return $this;
    }

    public function targetArea(int $range, int $radius): self
    {
        $this->targetSelection = new Area(new HighestThreat(), $range, $radius);
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
        $buff = new Debuff($this->name, $unit);
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