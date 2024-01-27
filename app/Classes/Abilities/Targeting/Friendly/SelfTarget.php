<?php

namespace App\Classes\Abilities\Targeting\Friendly;

use App\Classes\Abilities\Targeting\Abstracts\Targeting;
use App\Classes\Combat\Battlefield;
use App\Classes\Units\Abstracts\Unit;
use Illuminate\Support\Collection;

class SelfTarget implements Targeting
{
    public function select(Battlefield $battlefield, Unit $source): Collection
    {
        return new Collection([$source]);
    }
}