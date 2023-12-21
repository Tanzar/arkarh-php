<?php

namespace App\Classes\Combat;

class PositionFormula
{

    public static function calc(int $start, int $index): int
    {
        return $start + ceil($index / 2) * (($index % 2) ? -1 : 1 );
    }
}