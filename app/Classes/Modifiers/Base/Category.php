<?php

namespace App\Classes\Modifiers\Base;

enum Category: string
{
    case Attack = 'attack';
    case Defense = 'defense';
    case SpellPower = 'spellPower';
    case Health = 'health';
    case Armor = 'armor';
    case Ward = 'ward';
    case Speed = 'speed';
    case Morale = 'morale';
    case ArmorCap = 'armorCap';
    case WardCap = 'wardCap';
    case MaxDamageTaken = 'maxDamageTaken';
    case HealOverTime = 'healOverTime';
    case DamageMultiplier = 'damageMultiplier';
    case DamageTakenMultiplier = 'damageTakenMultiplier';
    case HealTakenMultiplier = 'healTakenMultiplier';
    case Threat = 'threat';
    case BonusDamage = 'bonusDamage';
    case Lifesteal = 'lifesteal';
    case Range = 'range';
    case MoraleDamage = 'moraleDamage';
    case DamageImmunity = 'damageImmunity';
    case Hardened = 'hardened';
}