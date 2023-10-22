<?php

namespace App\Classes\Game\Exceptions;

class FactionNotFoundException extends \Exception
{
    public function __construct(string $scriptName)
    {
        parent::__construct("Faction with script name: " . $scriptName . " not found.");
    }
}