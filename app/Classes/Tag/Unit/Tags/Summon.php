<?php

namespace App\Classes\Tag\Unit\Tags;

use App\Classes\Tag\Unit\Tag;
use App\Classes\Tag\Unit\TagBuilder;

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

    protected function alter(TagBuilder $builder): void
    {

    }

}