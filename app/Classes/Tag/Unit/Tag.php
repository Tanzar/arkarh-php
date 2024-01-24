<?php

namespace App\Classes\Tag\Unit;

use App\Classes\Modifiers\Category;
use App\Classes\Modifiers\Modifier;
use App\Classes\Units\Abstracts\Unit;
use Closure;

abstract class Tag implements TagBuilder
{

    private string $name;
    private string $uniqueGroup;

    private ?Unit $unit = null;

    public function __construct()
    {
        $this->name = $this->name();
        $this->uniqueGroup = $this->uniqueGroup();
    }

    abstract protected function name() : string;

    abstract protected function uniqueGroup() : string;

    public function getName(): string
    {
        return $this->name;
    }

    public function getUniqueGroup(): string
    {
        return $this->uniqueGroup;
    }

    public function alterUnit(Unit $unit): void
    {
        $this->unit = $unit;
        $this->alter($this);
        $this->unit = null;
    }

    abstract protected function alter(TagBuilder $builder): void;

    public function modifier(string $name, Category $category, Closure $function): TagBuilder
    {
        $modifier = new Modifier($name, $category);
        $function($modifier);
        $this->unit->applyModifier($modifier);
        return $this;
    }



}