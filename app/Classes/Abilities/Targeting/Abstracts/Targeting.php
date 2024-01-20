<?php

namespace App\Classes\Abilities\Targeting\Abstracts;

use App\Classes\Combat\Battlefield;
use App\Classes\Units\Abstracts\Unit;
use Illuminate\Support\Collection;

interface Targeting
{
    public function select(Battlefield $battlefield, Unit $source): Collection;
}