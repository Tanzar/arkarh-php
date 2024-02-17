<?php

namespace App\Classes\Abilities\Buff;
use App\Classes\Abilities\Shared\Ability;
use App\Classes\Abilities\Shared\Trigger;
use App\Classes\Abilities\Targeting\Abstracts\Targeting;
use App\Classes\Abilities\Targeting\Friendly\SelfTarget;
use App\Classes\Combat\Battlefield;
use App\Classes\Modifiers\Modifier;
use App\Classes\Units\Abstracts\Unit;
use Illuminate\Support\Collection;

class Buff extends Ability
{
    
    private Targeting $targeting;

    private Collection $modifiers;

    public function __construct(String $name, Unit $unit)
    {
        parent::__construct($name, $unit);
        $this->modifiers = new Collection();
        $this->targeting = new SelfTarget();
    }

    public function trigger(Trigger $trigger): void
    {
        $this->setTrigger($trigger);
    }

    public function setTargeting(Targeting $targeting): void
    {
        $this->targeting = $targeting;
    }

    public function addModifier(Modifier $builder): void
    {
        $builder->positive();
        $builder->source($this->getSource());
        $this->modifiers->add($builder);
    }

    protected function action(Battlefield $battlefield): bool
    {
        $source = $this->getSource();
        $targets = $this->targeting->select($battlefield, $source);
        /** @var Unit $target */
        foreach($targets as $target) {
            foreach ($this->modifiers as $builder) {
                $modifier = $builder->build();
                $text = $this->combatText($target, $modifier);
                $this->logUnitStage($target, $text);
                $target->applyModifier($modifier);
            }
        }
        return $targets->count() > 0;
    }

    private function combatText(unit $target, Modifier $modifier): string
    {
        return $target->getName() . ' was buffed with ' . $modifier->getName();
    }

}