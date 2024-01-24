<?php

namespace App\Classes\Tag\Unit\Tags;

use App\Classes\Tag\Unit\Tag;
use App\Classes\Tag\Unit\TagBuilder;
use App\Classes\Units\Abstracts\Unit;

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

    protected function alter(TagBuilder $builder): void
    {
        
    }

}