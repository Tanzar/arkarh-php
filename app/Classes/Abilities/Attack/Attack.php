<?php

namespace App\Classes\Abilities\Attack;

use App\Classes\Abilities\Targeting\Abstracts\Targeting;
use App\Classes\Abilities\Targeting\Enemies\Single;
use App\Classes\Abilities\Targeting\Primary\HighestThreat;
use App\Classes\Abilities\Targeting\Primary\SelectStrategy;
use App\Classes\Abilities\Targeting\Enemies\Area;
use App\Classes\Abilities\Shared\Ability;
use App\Classes\Abilities\Shared\Trigger;
use App\Classes\Combat\Battlefield;
use App\Classes\Modifiers\Category;
use App\Classes\Modifiers\Modifier;
use App\Classes\Shared\Types\School;
use App\Classes\Units\Abstracts\Unit;
use Illuminate\Support\Collection;

class Attack extends Ability
{
    private int $damage = 1;

    private bool $piercing = false;

    private float $physicalMultiplier = 0.05;

    private float $magicMultiplier = 0.1;

    private Targeting $targeting;

    private Collection $modifiers;

    public function __construct(String $name, Unit $unit)
    {
        parent::__construct($name, $unit);
        $this->setTrigger(Trigger::Action);
        $this->modifiers = new Collection();
        $this->targeting = new Single(new HighestThreat());
    }

    public function setDamage(int $damage): void
    {
        $this->damage = $damage;
    }

    public function setPiercing(): void
    {
        $this->piercing = true;
    }

    public function unsetPiercing(): void
    {
        $this->piercing = false;
    }

    public function setTargeting(Targeting $targeting): void
    {
        $this->targeting = $targeting;
    }

    public function addModifier(Modifier $builder): void
    {
        $builder->negative();
        $source = $this->getSource();
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
        return $this->strikeTargets($targets);
    }

    private function strikeTargets(Collection $targets): bool
    {
        $successfullHits = 0;
        $attack = $this->getSource()->getAttack();
        $spellPower = $this->getSource()->getSpellPower();
        $lifesteal = $this->getSource()->getModifiedValue(0, Category::Lifesteal, 0);
        /** @var Unit $target */
        foreach ($targets as $target) {
            $damage = $this->calculateDamage($target, $attack, $spellPower);
            $damageTaken = $target->takeDamage($damage, $this->getSchool(), $this->piercing);
            if ($damageTaken > 0) {
                foreach ($this->modifiers as $builder) {
                    $modifier = $builder->build();
                    $target->applyModifier($modifier);
                }
                $this->logUnitStage($target, $this->combatText($target, $damageTaken));
                $this->lifesteal($lifesteal, $damageTaken);
                $successfullHits++;
            }
        }
        return $successfullHits > 0;
    }

    private function calculateDamage(Unit $target, int $attack, int $spellPower): int
    {
        if ($this->getSchool() === School::Physical) {
            return $this->calculatePhysicalDamage($attack, $target);
        } else {
            return $this->calculateSpellDamage($spellPower);
        }
    }

    private function calculatePhysicalDamage(int $attack, Unit $target): int
    {
        $defense = $target->getDefense();
        $multiplier = 1 + ($attack - $defense) * $this->physicalMultiplier;
        return max(1, $this->damage * $multiplier);
    }

    private function calculateSpellDamage(int $spellPower): int
    {
        $multiplier = 1 + $spellPower * $this->magicMultiplier;
        return max(1, $this->damage * $multiplier);
    }

    private function combatText(unit $target, int $damageTaken): string
    {
        return $target->getName() . ' takes ' . $damageTaken . ' ' . $this->getSchool()->value . ' damage,';
    }

    private function lifesteal(int $lifesteal, int $damageTaken): void
    {
        if ($lifesteal > 0) {
            $multiplier = $lifesteal / 100;
            $health = max(1, $damageTaken * $multiplier);
            $source = $this->getSource();
            $healed = $source->heal($health);
            if ($healed > 0) {
                $text = $source->getName() . ' heals for ' . $healed . '.';
                $this->logUnitStage($source, $text);
            }
        }
    }
}