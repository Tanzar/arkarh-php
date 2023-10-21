<?php

namespace App\Classes\Abilities\Shared;

enum Trigger: string
{
    case Action = 'action';
    case DamageTake = 'damageTake';
    case Death = 'death';
    case Entry = 'entry';
    case Heal = 'heal';
    case Dispell = 'dispell';
}