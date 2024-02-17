<?php

namespace App\Classes\Abilities\Heal;

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
use App\Classes\Shared\Types\School;
use Closure;
use Illuminate\Support\Collection;

class HealBuilder implements AbilityBuilder
{
    use BasicAbilityBuilder;

    private int $value = 1;

    private School $defaultSchool = School::Physical;

    private Trigger $trigger = Trigger::Action;

    private ?Targeting $targetSelection;

    private Collection $modifiers;

    public function __construct()
    {
        $this->modifiers = new Collection();
    }

    public function trigger(Trigger $trigger): HealBuilder
    {
        $this->trigger = $trigger;
        return $this;
    }

    public function value(int $value): HealBuilder
    {
        if ($value > 0) {
            $this->value = $value;
        }
        return $this;
    }

    public function targetSelf(): HealBuilder
    {
        $this->targetSelection = new SelfTarget();
        return $this;
    }

    public function targetByCount(int $range, int $count): HealBuilder
    {
        $this->targetSelection = new Count($range, $count);
        return $this;
    }

    public function targetSingle(int $range): HealBuilder
    {
        $this->targetSelection = new Single($range);
        return $this;
    }

    public function targetArea(int $range, int $radius): HealBuilder
    {
        $this->targetSelection = new Area($range, $radius);
        return $this;
    }
    
    public function applies(string $name, Category $category, Closure $function): HealBuilder
    {
        $modifier = new Modifier($name, $category);
        $function($modifier);
        $this->modifiers->add($modifier);
        return $this;
    }

    
    public function build(Unit $unit): Ability
    {
        $heal = new Heal($this->name, $unit);
        $this->apply($heal);
        if ($heal->getSchool() === School::Uncategorized) {
            $heal->setSchool($this->defaultSchool);
        }
        $heal->setValue($this->value);
        $heal->trigger($this->trigger);
        if (isset($this->targetSelection)) {
            $heal->setTargeting($this->targetSelection);
        }
        foreach ($this->modifiers as $builder) {
            $heal->addModifier($builder);
        }
        return $heal;
    }
}