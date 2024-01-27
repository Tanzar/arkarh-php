<?php

namespace App\Classes\Abilities\Heal;
use App\Classes\Abilities\Shared\Ability;
use App\Classes\Abilities\Shared\Trigger;
use App\Classes\Abilities\Targeting\Abstracts\Targeting;
use App\Classes\Abilities\Targeting\Friendly\SelfTarget;
use App\Classes\Combat\Battlefield;
use App\Classes\Modifiers\Modifier;
use App\Classes\Shared\Types\School;
use App\Classes\Units\Abstracts\Unit;
use Illuminate\Support\Collection;

class Heal extends Ability
{
    private int $value = 1;

    private float $modifier = 0.1;

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

    public function setValue(int $value): void
    {
        if ($value > 0) {
            $this->value = $value;
        }
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
        if ($this->getSchool() === School::Uncategorized) {
            return false;
        }
        $source = $this->getSource();
        $targets = $this->targeting->select($battlefield, $source);
        $healValue = $this->calculateHealValue();
        $healed = false;
        /** @var Unit $target */
        foreach($targets as $target) {
            $healedValue = $target->heal($healValue);
            if ($healedValue > 0) {
                $healed = true;
                $text = $this->combatText($target, $healedValue);
                $this->logUnitStage($target, $text);
                foreach ($this->modifiers as $builder) {
                    $modifier = $builder->build();
                    $target->applyModifier($modifier);
                }
            }
        }
        return $healed;
    }

    private function calculateHealValue(): int
    {
        if ($this->getSchool() === School::Physical) {
            return $this->value;
        }
        $spellPower = $this->getSource()->getSpellPower();
        $modifier = 1 + ($this->modifier * $spellPower);
        return floor($this->value * $modifier);
    }

    private function combatText(unit $target, int $healedValue): string
    {
        return $target->getName() . ' was healed for ' . $healedValue . ' ' . $this->getSchool()->value . ' damage.';
    }

}