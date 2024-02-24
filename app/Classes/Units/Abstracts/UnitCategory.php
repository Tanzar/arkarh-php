<?php

namespace App\Classes\Units\Abstracts;

interface UnitCategory
{

    public function isRessurectable(): bool;

    public function canBeRisen(): bool;
}