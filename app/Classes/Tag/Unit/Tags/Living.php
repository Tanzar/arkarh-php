<?php

namespace App\Classes\Tag\Unit\Tags;
use App\Classes\Tag\Unit\Tag;
use App\Classes\Tag\Unit\TagBuilder;

class Living extends Tag
{

    protected function name(): string
    {
        return 'Living';
    }

    protected function uniqueGroup(): string
    {
        return 'category';
    }

    protected function alter(TagBuilder $builder): void
    {
        
    }

}