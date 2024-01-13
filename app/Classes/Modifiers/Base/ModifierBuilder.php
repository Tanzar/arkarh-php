<?php

namespace App\Classes\Modifiers\Base;

use App\Classes\Shared\Types\Dispells;
use App\Classes\Shared\Types\School;

interface ModifierBuilder
{
    public function stacks(int $value): ModifierBuilder;

    public function maxStacks(int $value): ModifierBuilder;

    public function stacksChange(int $value): ModifierBuilder;

    public function duration(int $value): ModifierBuilder;

    public function unlimitedDuration(): ModifierBuilder;

    public function stackValue(float $value): ModifierBuilder;

    public function school(School $school): ModifierBuilder;

    public function dispell(Dispells $dispell): ModifierBuilder;

    public function changeOnApply(): ModifierBuilder;
    
    public function noChangeOnApply(): ModifierBuilder;

    public function changeOnDurationReduction(): ModifierBuilder;

    public function noChangeOnDurationReduction(): ModifierBuilder;

    public function negative(): ModifierBuilder;

    public function positive(): ModifierBuilder;

    public function build(): Modifier;

}