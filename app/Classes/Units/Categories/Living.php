<?php

namespace App\Classes\Units\Categories;
use App\Classes\Units\Abstracts\UnitCategory;

class Living implements UnitCategory
{

    public function isRessurectable(): bool
    {
        return true;
    }

    public function canBeRisen(): bool
    {
        return true;
    }

}