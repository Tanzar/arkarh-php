<?php

namespace App\Classes\Modifiers;

class ModifierIdGenerator
{
    private int $value = 1;

    public static function get(): int
    {
        $val = self::$value;
        self::$value++;
        return $val;
    }
}