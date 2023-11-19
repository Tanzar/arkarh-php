<?php

namespace App\Classes\Units\Abstracts;

class UnitIdGenerator
{
    private int $value = 1;

    public static function get(): int
    {
        $val = self::$value;
        self::$value++;
        return $val;
    }
}