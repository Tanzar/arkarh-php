<?php

namespace App\Classes\Modifiers;

use App\Classes\Shared\Types\Dispells;
use App\Classes\Shared\Types\School;
use App\Classes\Shared\Utility\IdGenerator;
use App\Classes\Units\Abstracts\Unit;

class Modifier implements ModifierBuilder
{
    private int $id;
    private string $name;
    private ?Unit $source = null;
    private int $stacks = 1;
    private int $maxStacks = 1;
    private int $stacksChange = 1;
    private int $duration = -1;
    private Category $category;
    private float $value = 0;
    private School $school = School::Uncategorized;
    private Dispells $dispell = Dispells::None;
    private bool $changeOnApply = true;
    private bool $changeOnDurationReduction = false;
    private bool $negative = false;
    private bool $uniqueByUnitType = false;

    public function __construct(string $name, Category $category)
    {
        $this->id = IdGenerator::get();
        $this->name = $name;
        $this->category = $category;
    }

    /**
     * Get the value of name
     */ 
    public function getName(): string
    {
        return $this->name;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getStacks(): int
    {
        return $this->stacks;
    }

    public function getSchool(): School
    {
        return $this->school;
    }

    public function source(Unit $unit): void
    {
        $this->source = $unit;
    }

    public function stacks(int $value): ModifierBuilder
    {
        if ($value > 0 && $value <= $this->maxStacks) {
            $this->stacks = $value;
        }
        return $this;
    }

    public function maxStacks(int $value): ModifierBuilder
    {
        if ($value >= 1) {
            $this->maxStacks = $value;
            $this->stacks = min($this->stacks, $this->maxStacks);
        }
        return $this;
    }

    public function stacksChange(int $value): ModifierBuilder
    {
        $this->stacksChange = $value;
        return $this;
    }

    public function duration(int $value): ModifierBuilder
    {
        if ($value > 0) {
            $this->duration = $value;
        }
        return $this;
    }

    public function unlimitedDuration(): ModifierBuilder
    {
        $this->duration = -1;
        return $this;
    }

    public function stackValue(float $value): ModifierBuilder
    {
        if ($value > 0) {
            $this->value = $value;
        }
        return $this;
    }

    public function school(School $school): ModifierBuilder
    {
        $this->school = $school;
        return $this;
    }

    public function dispell(Dispells $dispell): ModifierBuilder
    {
        $this->dispell = $dispell;
        return $this;
    }

    public function changeOnApply(): ModifierBuilder
    {
        $this->changeOnApply = true;
        return $this;
    }
    
    public function noChangeOnApply(): ModifierBuilder
    {
        $this->changeOnApply = false;
        return $this;
    }

    public function changeOnDurationReduction(): ModifierBuilder
    {
        $this->changeOnDurationReduction = true;
        return $this;
    }

    public function noChangeOnDurationReduction(): ModifierBuilder
    {
        $this->changeOnDurationReduction = false;
        return $this;
    }

    public function negative(): ModifierBuilder
    {
        $this->negative = true;
        return $this;
    }

    public function positive(): ModifierBuilder
    {
        $this->negative = false;
        return $this;
    }

    public function uniquePerUnit(): ModifierBuilder
    {
        $this->uniqueByUnitType = false;
        return $this;
    }

    public function uniquePerUnitType(): ModifierBuilder
    {
        $this->uniqueByUnitType = true;
        return $this;
    }

    /**
     * Changes unit stacks count
     *
     * @param integer $stacks number of stacks to apply
     * @return integer stacks count change, positive if stacks increase, negative if stacks decrease
     */
    public function changeStacks(): int
    {
        if ($this->stacksChange === 0) {
            return 0;
        } elseif ($this->stacksChange > 0) {
            return $this->increaseStacks();
        } else {
            return $this->decreaseStacks();
        }
    }

    private function increaseStacks(): int
    {
        $freeStacks = $this->maxStacks - $this->stacks;
        $stacksChange = min($freeStacks, $this->stacksChange);
        $this->stacks += $stacksChange;
        return $stacksChange;
    }

    private function decreaseStacks(): int
    {
        $stacksChange = min($this->stacksChange, $this->stacks);
        $this->stacks -= $stacksChange;
        return $stacksChange;
    }

    public function reduceDuration(): void
    {
        if ($this->duration > 0) {
            $this->duration--;
        }
        if ($this->changeOnDurationReduction) {
            $this->changeStacks();
        }
    }

    public function getTotalValue(): float
    {
        return $this->stacks * $this->getStackValue();
    }

    /**
     * Get the value of value
     */ 
    public function getStackValue(): float
    {
        if ($this->negative) {
            return -1 * $this->value;
        } else {
            return $this->value;
        }
    }

    public function areSame(Modifier $modifier): bool
    {
        if ($this->source !== null && $this->uniqueByUnitType) {
            return $this->source->getTypeId() === $modifier->source->getTypeId();
        }
        return $this->id === $modifier->id;
    }

    public function canChangeOnApply(): bool
    {
        return $this->changeOnApply;
    }

    public function canDispell(Dispells $dispell): bool
    {
        return $this->dispell === $dispell;
    }

    public function isNegative(): bool
    {
        return $this->negative;
    }

    public function isPositive(): bool
    {
        return !$this->negative;
    }

    public function build(): Modifier
    {
        $copy = new Modifier($this->name, $this->category);
        $copy->id = $this->id;
        $copy->stacks = $this->stacks;
        $copy->maxStacks = $this->maxStacks;
        $copy->stacksChange = $this->stacksChange;
        $copy->duration = $this->duration;
        $copy->value = $this->value;
        $copy->school = $this->school;
        $copy->dispell = $this->dispell;
        $copy->changeOnApply = $this->changeOnApply;
        $copy->changeOnDurationReduction = $this->changeOnDurationReduction;
        $copy->negative = $this->negative;
        $copy->uniqueByUnitType = $this->uniqueByUnitType;
        return $copy;
    }

}