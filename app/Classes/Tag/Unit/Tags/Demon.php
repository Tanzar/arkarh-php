<?php

namespace App\Classes\Tag\Unit\Tags;

use App\Classes\Modifiers\Base\ModifierBuilderContainer;
use App\Classes\Tag\Unit\Tag;

class Demon extends Tag
{

    protected function name(): string
    {
        return "Demon";
    }

    protected function uniqueGroup(): string
    {
        return "category";
    }

    protected function modifiers(ModifierBuilderContainer $modifiers): void
    {
        
    }

}