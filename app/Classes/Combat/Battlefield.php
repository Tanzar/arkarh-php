<?php

namespace App\Classes\Combat;

class Battlefield
{
    private ?self $instance = null;

    private Side $attackers;

    private Side $defenders;

    private function __construct(Side $attackers, Side $defenders)
    {
        $this->attackers = $attackers;
        $this->defenders = $defenders;
    }

    public static function getInstance(Side $attackers = null, Side $defenders = null): self
    {
        if (self::$instance === null) {
            self::$instance = new self($attackers, $defenders);
        }
        return self::$instance;
    }
    
    
}