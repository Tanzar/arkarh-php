<?php

namespace App\Classes\Tag\Unit;

use App\Classes\Modifiers\ModifierBuilder;
use App\Classes\Modifiers\ModifierBuilderContainer;

abstract class Tag
{

    private string $name;
    private string $uniqueGroup;

    private ModifierBuilderContainer $modifiers;

    public function __construct()
    {
        $this->name = $this->name();
        $this->uniqueGroup = $this->uniqueGroup();
        $this->modifiers = new ModifierBuilderContainer();
        $this->modifiers($this->modifiers);
    }

    protected abstract function name() : string;

    protected abstract function uniqueGroup() : string;

    protected abstract function modifiers(ModifierBuilderContainer $modifiers): void;


    public function getName(): string
    {
        return $this->name;
    }

    public function getUniqueGroup(): string
    {
        return $this->uniqueGroup;
    }

    protected function addModifier(ModifierBuilder $modifierBuilder): void
    {
        $this->modifiers[] = $modifierBuilder;
    }

    public function getModifiers(): array
    {
        $modifiers = [];
        /** @var ModifierBuilder $builder */
        foreach ($this->modifiers as $builder) {
            $modifiers[] = $builder->build();
        }
        return $modifiers;
    }
}