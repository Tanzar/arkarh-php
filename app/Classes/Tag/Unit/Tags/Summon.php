<?php

namespace App\Classes\Tag\Unit\Tags;

use App\Classes\Modifiers\Base\ModifierBuilderContainer;
use App\Classes\Tag\Unit\Tag;

class Summon extends Tag
{

    protected function name(): string
    {
        return 'Summon';
    }

    protected function uniqueGroup(): string
    {
        return 'summon';
    }

    protected function modifiers(ModifierBuilderContainer $modifiers): void
    {
        
    }

}