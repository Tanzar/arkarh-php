<?php

namespace App\Classes\Tag\Unit\Tags;

use App\Classes\Modifiers\Category;
use App\Classes\Modifiers\ModifierBuilder;
use App\Classes\Shared\Types\School;
use App\Classes\Tag\Unit\Tag;
use App\Classes\Tag\Unit\TagBuilder;

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

    protected function alter(TagBuilder $builder): void
    {
        $builder->modifier('Fire weakness', Category::DamageTakenMultiplier, function(ModifierBuilder $builder) {
            $builder
                ->school(School::Fire)
                ->stackValue(0.5)
                ->negative();
        });
    }

}