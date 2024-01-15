<?php

namespace App\Classes\Modifiers;

use Illuminate\Support\Collection;

class ModifierBuilderContainer
{
    private Collection $modifiers;

    public function __construct()
    {
        $this->modifiers = new Collection();
    }

    public function getModifiers(): Collection
    {
        return $this->modifiers;
    }

    public function add(string $name, Category $category): ModifierBuilder
    {
        $builder = new Modifier($name, $category);
        $this->modifiers->push($builder);
        return $builder;
    }
}