<?php

namespace App\Classes\Combat;

enum BattleState: string
{
    case Ongoing = 'ongoing';
    case AttackersVictory = 'attackersVictory';
    case DefendersVictory = 'defendersVictory';
    case Draw = 'draw';
}