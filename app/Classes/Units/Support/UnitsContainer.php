<?php

namespace App\Classes\Units\Support;

use App\Classes\Units\Abstracts\Unit;
use Illuminate\Database\Eloquent\Collection;

class UnitsContainer
{
    private Collection $units;

    public function __construct()
    {
        $this->units = new Collection();
    }

    public function getUnits(): Collection
    {
        return $this->units;
    }

    public function addUnit(?Unit $unit): void
    {
        if ($unit !== null) {
            $this->units->push($unit);
        }
    }
}