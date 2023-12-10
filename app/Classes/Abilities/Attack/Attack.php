<?php

namespace App\Classes\Abilities\Attack;
use App\Classes\Abilities\Shared\Ability;
use App\Classes\Abilities\Shared\Trigger;
use App\Classes\Abilities\Targeting\Abstracts\TargetSelectionStartegy;
use App\Classes\Abilities\Targeting\TargetByThreat;
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

    private TargetSelectionStartegy $targetSelection;

    public function __construct(Unit $unit)
    {
        parent::__construct($unit);
        $this->setTrigger(Trigger::Action);
        $this->targetSelection = new TargetByThreat($this->area, $this->bothLines);
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

    public function strikeBothLines(): void
    {
        $this->bothLines = true;
    }

    public function strikeSingleLine(): void
    {
        $this->bothLines = false;
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

    public function setTargetSelection(TargetByThreat $targetSelection): void
    {
        $this->targetSelection = $targetSelection;
    }

    protected function action(Battlefield $battlefield): bool
    {
        if ($this->school === School::Uncategorized) {
            return false;
        }
        $source = $this->getSource();
        $side = $battlefield->getOppositeSide($source);
        $range = $source->getModifiedValue($this->range, Category::Range, 1);
        $targets = $this->targetSelection->selectTargets($side, $source->getPosition(), $range);
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
                $text = $source->getName() . 'heals for ' . $healed . '.';
                $this->logUnitStage($source, $text);
            }
        }
    }
}