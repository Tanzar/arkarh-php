<?php

namespace App\Classes\Combat;

use Illuminate\Database\Eloquent\Collection;

class ArmyPatterns
{
    private Collection $armies;

    public function __construct()
    {
        $this->armies = new Collection();
    }

    public function addPattern(ArmyPattern $army): void
    {
        $this->armies->push($army);
    }

    public function getPatterns(): Collection
    {
        return $this->armies;
    }
}