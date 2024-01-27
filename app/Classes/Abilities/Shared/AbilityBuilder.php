<?php

namespace App\Classes\Abilities\Shared;

use App\Classes\Shared\Types\School;
use App\Classes\Units\Abstracts\Unit;

interface AbilityBuilder
{
    public function build(Unit $unit): Ability;

    public function name(string $name): self;

    public function charges(int $charges): self;

    public function unlimitedCharges(): self;

    public function initialCooldown(int $cooldown): self;

    public function cooldown(int $cooldown): self;

    public function school(School $school): self;
}