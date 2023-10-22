<?php

namespace App\Classes\Game\Exceptions;

class UndefinedScriptException extends \Exception
{
    public function __construct(string $type, string $script)
    {
        parent::__construct("Undefined $type script: $script");
    }
}