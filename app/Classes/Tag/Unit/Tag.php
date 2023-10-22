<?php

namespace App\Classes\Tag\Unit;

use App\Classes\Modifiers\ModifierBuilder;

abstract class Tag
{

    private string $name;
    private string $uniqueGroup;

    private array $modifiers;

    public function __construct(string $name, string $uniqueGroup)
    {
        $this->name = $name;
        $this->uniqueGroup = $uniqueGroup;
        $this->modifiers = [];
    }

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