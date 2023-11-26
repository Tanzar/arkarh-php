<?php

namespace App\Classes\Abilities\Attack;
use App\Classes\Abilities\Shared\Ability;
use App\Classes\Abilities\Shared\Trigger;
use App\Classes\Abilities\Targeting\Abstracts\TargetSelectionStartegy;
use App\Classes\Abilities\Targeting\TargetByThreat;
use App\Classes\Combat\Battlefield;
use App\Classes\Combat\CombatLog;
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

    private float $physicalMultiplier = 0.05;

    private float $magicMultiplier = 0.1;

    private TargetSelectionStartegy $targetSelection;

    public function __construct(Unit $unit)
    {
        parent::__construct($unit);
        $this->setTrigger(Trigger::Action);
        $this->targetSelection = new TargetByThreat($this->area, $this->bothLines);
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
        if ($this->school === School::Physical) {
            return $this->physicalAttack($targets, $source);
        } else {
            return $this->magicalAttack($targets, $source);
        }
    }

    private function physicalAttack(Collection $targets, Unit $source): bool
    {
        $successfullHits = 0;
        $attack = $source->getAttack();
        /** @var Unit $target */
        foreach ($targets as $target) {
            $defense = $target->getDefense();
            $multiplier = ($attack - $defense) * $this->physicalMultiplier;
            $damage = max(1, $this->damage * $multiplier);
            $damageTaken = $target->takeDamage($damage, $this->school, $source);
            if ($damageTaken > 0) {
                $this->logAttack($target, $this->school, $damageTaken);
                $successfullHits++;
            }
        }
        return $successfullHits > 0;
    }

    private function magicalAttack(Collection $targets, Unit $source): bool
    {
        $successfullHits = 0;
        $spellPower = $source->getSpellPower();
        $multiplier = $spellPower * $this->magicMultiplier;
        /** @var Unit $target */
        foreach ($targets as $target) {
            $damage = max(1, $this->damage * $multiplier);
            $damageTaken = $target->takeDamage($damage, $this->school, $source);
            if ($damageTaken > 0) {
                $this->logAttack($target, $this->school, $damageTaken);
                $successfullHits++;
            }
        }
        return $successfullHits > 0;
    }

    public function logAttack(Unit $target, School $school, int $damage): void
    {
        $options = [
            'side' => $this->getSource()->isAttacker() ? 'attacker' : 'defender',
            'targetPosition' => $target->getPosition(),
            'targetName' => $target->getName(),
            'damage' => $damage,
            'school' => _($school->value)
        ];
        CombatLog::getInstance()->saveAction('attack', $this->getSource(), $options);
    }
}