<?php

namespace App\Classes\Modifiers;

use App\Classes\Shared\Types\Dispells;
use App\Classes\Shared\Types\School;
use Illuminate\Support\Collection;

class ModifierBuilder
{
    private int $modifierId;

    private Collection $options;

    public function __construct(string $name, Category $category)
    {
        $this->modifierId = ModifierIdGenerator::get();
        $options = new Collection();
        $options->put('name', $name);
        $options->put('category', $category);
        $this->options = $options;
    }

    public function unique(string $value): ModifierBuilder
    {
        $this->options->put('unique', $value);
        return $this;
    }

    public function startStacks(int $value): ModifierBuilder
    {
        $this->options->put('stacks', $value);
        return $this;
    }

    public function maxStacks(int $value): ModifierBuilder
    {
        $this->options->put('maxStacks', $value);
        return $this;
    }

    public function value(float $value): ModifierBuilder
    {
        $this->options->put('value', $value);
        return $this;
    }

    public function school(School $value): ModifierBuilder
    {
        $this->options->put('school', $value);
        return $this;
    }

    public function dispell(Dispells $value): ModifierBuilder
    {
        $this->options->put('dispell', $value);
        return $this;
    }

    public function duration(int $value): ModifierBuilder
    {
        $this->options->put('duration', $value);
        return $this;
    }

    public function stacksIncrease(): ModifierBuilder
    {
        $this->options->put('increase', true);
        return $this;
    }
    
    public function stacksDecrease(): ModifierBuilder
    {
        $this->options->put('increase', false);
        return $this;
    }

    public function negative(): ModifierBuilder
    {
        $this->options->put('negative', true);
        return $this;
    }

    public function positive(): ModifierBuilder
    {
        $this->options->put('negative', false);
        return $this;
    }

    public function build(): Modifier
    {
        $modifier = new Modifier(
            $this->modifierId, 
            $this->options->get('name'), 
            $this->options->get('category')
        );
        if ($this->options->has('unique')) {
            $modifier->setUnique($this->options->get('uniqie'));
        }
        if ($this->options->has('stacks')) {
            $modifier->setStartStacks($this->options->get('stacks'));
        }
        if ($this->options->has('maxStacks')) {
            $modifier->setMaxStacks($this->options->get('maxStacks'));
        }
        if ($this->options->has('value')) {
            $modifier->setValue($this->options->get('value'));
        }
        if ($this->options->has('school')) {
            $modifier->setSchool($this->options->get('school'));
        }
        if ($this->options->has('dispell')) {
            $modifier->setDispell($this->options->get('dispell'));
        }
        if ($this->options->has('duration')) {
            $modifier->setDuration($this->options->get('duration'));
        }
        if ($this->options->has('increase')) {
            $modifier->setIncrease($this->options->get('increase'));
        }
        if ($this->options->has('negative')) {
            $modifier->setNegative($this->options->get('negative'));
        }
        return $modifier;
    }

}