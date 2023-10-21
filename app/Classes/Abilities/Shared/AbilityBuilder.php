<?php

namespace App\Classes\Abilities\Shared;

interface AbilityBuilder
{
    public function build(): Ability;
}