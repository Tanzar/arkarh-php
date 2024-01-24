<?php

namespace App\Classes\Tag\Unit\Tags;

use App\Classes\Modifiers\Category;
use App\Classes\Modifiers\ModifierBuilder;
use App\Classes\Tag\Unit\Tag;
use App\Classes\Tag\Unit\TagBuilder;

class Vampire extends Tag
{

    protected function name(): string
    {
        return 'Vampire';
    }

    protected function uniqueGroup(): string
    {
        return '';
    }

    protected function alter(TagBuilder $builder): void
    {
        $builder->modifier('Fire weakness', Category::Lifesteal, function(ModifierBuilder $builder) {
            $builder->stackValue(20);
        });
    }

}