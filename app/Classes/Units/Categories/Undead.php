<?php

namespace App\Classes\Units\Categories;
use App\Classes\Units\Abstracts\UnitCategory;

class Undead implements UnitCategory
{

    public function isRessurectable(): bool
    {
        return false;
    }

    public function canBeRisen(): bool
    {
        return true;
    }

}