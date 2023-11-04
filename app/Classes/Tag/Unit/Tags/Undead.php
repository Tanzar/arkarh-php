<?php

namespace App\Classes\Tag\Unit\Tags;

use App\Classes\Modifiers\Category;
use App\Classes\Modifiers\ModifierBuilderContainer;
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
        $modifiers->add('Fire weakness', Category::DamageTakenMultiplier)->school(School::Fire)->value(0.5)->negative();
    }

}