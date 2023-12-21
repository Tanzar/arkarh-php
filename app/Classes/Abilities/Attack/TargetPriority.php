<?php

namespace App\Classes\Abilities\Attack;

enum TargetPriority: string
{
    case Threat = 'threat';
    case HighestLevel = 'highestLevel';
    case LowestLevel = 'lowestLevel';
    case HighestHealth = 'highestHealth';
    case LowestHealth = 'lowestHealth';
    
}