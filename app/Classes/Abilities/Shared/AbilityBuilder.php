<?php

namespace App\Classes\Abilities\Shared;

use App\Classes\Units\Abstracts\Unit;

interface AbilityBuilder
{
    public function build(Unit $unit): Ability;

}