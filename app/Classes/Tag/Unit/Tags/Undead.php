<?php

namespace App\Classes\Tag\Unit\Tags;

use App\Classes\Modifiers\Base\Category;
use App\Classes\Modifiers\Base\ModifierBuilderContainer;
use App\Classes\Shared\Types\School;
use App\Classes\Tag\Unit\Tag;

class Undead extends Tag
{

    protected function name(): string
    {
        return "Undead";
    }

    protected function uniqueGroup(): string
    {
        return "category";
    }

    protected function modifiers(ModifierBuilderContainer $modifiers): void
    {
        $modifiers
            ->add('Fire weakness', Category::DamageTakenMultiplier)
            ->school(School::Fire)
            ->stackValue(0.5)
            ->negative();
    }

}