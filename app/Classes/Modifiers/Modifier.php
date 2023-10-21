<?php

namespace App\Classes\Modifiers;

use App\Classes\Shared\Types\Dispells;
use App\Classes\Shared\Types\School;

class Modifier
{
    private int $id;

    private string $name;

    private string $uniqueGroup = '';

    private int $stacks = 1;
    private int $maxStacks = 1;

    private int $duration = -1;

    private Category $category;

    private float $value = 0;

    private School $school = School::Uncategorized;

    private Dispells $dispell = Dispells::None;

    private bool $increase = true;

    private bool $negative = false;

    public function __construct(int $id, string $name, Category $category)
    {
        $this->id = $id;
        $this->name = $name;
        $this->category = $category;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of name
     */ 
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the value of uniqueGroup
     */ 
    public function getUniqueGroup(): string
    {
        return $this->uniqueGroup;
    }

    /**
     * Set the value of uniqueGroup
     */ 
    public function setUnique(string $uniqueGroup): void
    {
        $this->uniqueGroup = $uniqueGroup;
    }

    /**
     * Get the value of stacks
     */ 
    public function getStacks(): int
    {
        return $this->stacks;
    }

    /**
     * Set the value of stacks
     */ 
    public function setStartStacks(int $stacks): void
    {
        if ($stacks >= 1 && $stacks <= $this->maxStacks) {
            $this->stacks = $stacks;
        }
    }

    /**
     * Changes unit stacks count
     *
     * @param integer $stacks number of stacks to apply
     * @return integer stacks count change, positive if stacks increase, negative if stacks decrease
     */
    public function changeStacks(int $stacks): int
    {
        if ($stacks > 0) {
            if ($this->increase) {
                return $this->increaseStacks($stacks);
            } else {
                return $this->decreaseStacks($stacks);
            }
        }
        return 0;
    }

    private function increaseStacks(int $stacks): int
    {
        if ($stacks === $this->maxStacks) {
            return 0;
        } else {
            $freeStacks = $this->maxStacks - $this->stacks;
            $stacksChange = min($freeStacks, $stacks);
            $this->stacks += $stacksChange;
            return $stacksChange;
        }
    }

    private function decreaseStacks(int $stacks): int
    {
        if ($stacks === 0) {
            return 0;
        } else {
            $stacksChange = min($stacks, $this->stacks);
            $this->stacks -= $stacksChange;
            return $stacksChange;
        }
    }

    /**
     * Get the value of maxStacks
     */ 
    public function getMaxStacks(): int
    {
        return $this->maxStacks;
    }

    /**
     * Set the value of maxStacks
     */ 
    public function setMaxStacks(int $maxStacks): void
    {
        if ($maxStacks >= 1) {
            $this->maxStacks = $maxStacks;
        }
        if ($this->stacks > $this->maxStacks) {
            $this->stacks = $this->maxStacks;
        }
    }

    /**
     * Get the value of duration
     */ 
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * Set the value of duration
     */ 
    public function setDuration(int $duration): void
    {
        if ($duration >= -1) {
            $this->duration = $duration;
        }
    }

    public function reduceDuration(): void
    {
        if ($this->duration > 0) {
            $this->duration--;
        }
    }

    /**
     * Get the value of category
     */ 
    public function getCategory(): Category
    {
        return $this->category;
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

    /**
     * Set the value of value
     */ 
    public function setValue(float $value): void
    {
        if ($value > 0) {
            $this->value = $value;
        }
    }

    /**
     * Get the value of school
     */ 
    public function getSchool(): School
    {
        return $this->school;
    }

    /**
     * Set the value of school
     */ 
    public function setSchool(School $school): void
    {
        $this->school = $school;
    }

    /**
     * Set the value of dispell
     */ 
    public function setDispell(Dispells $dispell)
    {
        $this->dispell = $dispell;
    }
    

    /**
     * Get the value of dispell
     */ 
    public function getDispell(): Dispells
    {
        return $this->dispell;
    }

    /**
     * Get the value of increase
     */ 
    public function getIncrease()
    {
        return $this->increase;
    }

    /**
     * Set the value of increase
     */ 
    public function setIncrease(bool $increase): void
    {
        $this->increase = $increase;
    }

    /**
     * Get the value of negative
     */ 
    public function isNegative(): bool
    {
        return $this->negative;
    }

    public function isPositive(): bool
    {
        return !$this->negative;
    }

    /**
     * Set the value of negative
     *
     * @return  self
     */ 
    public function setNegative(bool $negative): void
    {
        $this->negative = $negative;
    }
}