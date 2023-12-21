<?php

namespace App\Classes\Abilities\Attack;

use App\Classes\Abilities\Attack\Targeting\Primary\HighestThreat;
use App\Classes\Abilities\Attack\Targeting\Primary\SelectStrategy;
use App\Classes\Abilities\Attack\Targeting\TargetEnemies;
use App\Classes\Abilities\Shared\Ability;
use App\Classes\Abilities\Shared\Trigger;
use App\Classes\Combat\Battlefield;
use App\Classes\Modifiers\Category;
use App\Classes\Shared\Types\School;
use App\Classes\Units\Abstracts\Unit;
use Illuminate\Support\Collection;

class Attack extends Ability
{
    private int $range = 1;

    private int $damage = 1;

    private int $area = 0;

    private bool $bothLines = false;

    private School $school;

    private bool $piercing = false;

    private float $physicalMultiplier = 0.05;

    private float $magicMultiplier = 0.1;

    private TargetEnemies $targetSelection;

    public function __construct(Unit $unit)
    {
        parent::__construct($unit);
        $this->setTrigger(Trigger::Action);
        $this->targetSelection = new TargetEnemies(new HighestThreat());
    }

    protected function actionLog(): string
    {
        return $this->getSource()->getName() . ' starts attack.';
    }

    public function setRange(int $range): void
    {
        $this->range = $range;
    }

    public function setDamage(int $damage): void
    {
        $this->damage = $damage;
    }

    public function setArea(int $area): void
    {
        $this->area = $area;
    }

    public function setSchool(School $school): void
    {
        $this->school = $school;
    }

    public function setPiercing(): void
    {
        $this->piercing = true;
    }

    public function unsetPiercing(): void
    {
        $this->piercing = false;
    }

    public function setStrikeBothLines(): void
    {
        $this->bothLines = true;
    }

    public function setStrikeSingleLine(): void
    {
        $this->bothLines = false;
    }

    public function setTargetSelection(SelectStrategy $primaryTargetSelection): void
    {
        $this->targetSelection = new TargetEnemies($primaryTargetSelection);
    }

    protected function action(Battlefield $battlefield): bool
    {
        if ($this->school === School::Uncategorized) {
            return false;
        }
        $source = $this->getSource();
        $targets = $this->targetSelection->select($battlefield, $source, $this->range, $this->area, $this->bothLines);
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
            $damageTaken = $target->takeDamage($damage, $this->school, $this->piercing);
            if ($damageTaken > 0) {
                $this->logUnitStage($target, $this->combatText($target, $damageTaken));
                $this->lifesteal($lifesteal, $damageTaken);
                $successfullHits++;
            }
        }
        return $successfullHits > 0;
    }

    private function calculateDamage(Unit $target, int $attack, int $spellPower): int
    {
        if ($this->school === School::Physical) {
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
        return $target->getName() . ' takes ' . $damageTaken . ' ' . $this->school->value . ' damage,';
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