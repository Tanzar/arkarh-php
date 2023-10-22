<?php

namespace App\Classes\Game;


class GameConfig extends GameFactory
{
    private ?self $instance = null;

    private function __construct()
    {
        parent::__construct();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function factions(FactionsInterface $factions): void
    {

    }
}