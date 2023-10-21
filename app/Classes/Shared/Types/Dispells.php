<?php

namespace App\Classes\Shared\Types;

enum Dispells: string
{
    case None = 'none';
    case Magic = 'magic';
    case Diesease = 'diesease';
    case Curse = 'curse';
    case Bleed = 'bleed';
}