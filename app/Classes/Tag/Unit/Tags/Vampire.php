<?php

namespace App\Classes\Tag\Unit\Tags;

use App\Classes\Modifiers\Category;
use App\Classes\Modifiers\ModifierBuilderContainer;
use App\Classes\Tag\Unit\Tag;

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

    protected function modifiers(ModifierBuilderContainer $modifiers): void
    {
        $modifiers->add('vampirism', Category::Lifesteal)->value(20);
    }

}