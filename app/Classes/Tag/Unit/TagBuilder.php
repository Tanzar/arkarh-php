<?php

namespace App\Classes\Tag\Unit;

use App\Classes\Modifiers\Category;
use Closure;

interface TagBuilder
{

    public function modifier(string $name, Category $category, Closure $function): TagBuilder;
}