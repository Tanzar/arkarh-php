<?php

namespace App\Classes\Units\Exceptions;

class UnitNotFoundException extends \Exception
{
    public function __construct(string $scriptName)
    {
        parent::__construct("Unit with script name: " . $scriptName . " not found.");
    }
}