<?php

namespace App\Classes\Game;
use App\Classes\Factions\Testers\Testers;


class GameConfig extends GameFactory
{
    private static ?self $instance = null;

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
        $factions->add('testers', new Testers());
    }
}