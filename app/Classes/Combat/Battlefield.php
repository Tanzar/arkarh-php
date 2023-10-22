<?php

namespace App\Classes\Combat;

class Battlefield
{
    private ?self $instance = null;

    private function __construct()
    {}

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
}