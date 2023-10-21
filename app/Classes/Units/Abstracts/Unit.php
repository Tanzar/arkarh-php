<?php

namespace App\Classes\Units\Abstracts;

use App\Classes\Abilities\Shared\Abilities;
use App\Classes\Abilities\Shared\Ability;
use App\Classes\Modifiers\Category;
use App\Classes\Modifiers\Modifier;
use App\Classes\Modifiers\Modifiers;
use App\Classes\Shared\Types\Dispells;
use App\Classes\Shared\Types\School;

class Unit 
{
    //Unit script name, must be uniqie at least for faction
    private string $scriptName;

    //Unit display name
    private string $name;

    //Unit icon displayed in game
    private string $icon;

    private int $attack = 0;

    private int $defense = 0;

    private int $spellPower = 0;

    private int $health = 1;

    private int $maxHealth = 1;

    private int $armorCap = 75;

    private int $armor = 0;

    private int $wardCap = 75;

    private int $ward = 0;

    private int $speed = 1;

    //value used to determine unit will to fight
    private int $morale = 5000;
    private int $maxMorale = 5000;

    private int $moraleDamage = 5;

    private int $threat = 1;

    private Modifiers $modifiers;

    private Abilities $abilities;

    public function __construct(string $scriptName, string $name, string $icon)
    {
        $this->scriptName = $scriptName;
        $this->name = $name;
        $this->icon = $icon;
        $this->modifiers = new Modifiers();
        $this->abilities = new Abilities();
    }

    /**
     * Get the value of attack
     */ 
    public function getAttack(): int
    {
        return $this->getModifiedValue(
            $this->attack, 
            Category::Attack, 
            0
        );
    }

    /**
     * Set the value of attack, min 0
     */ 
    public function setAttack(int $attack): void
    {
        if ($attack >= 0) {
            $this->attack = $attack;
        }
    }

    /**
     * Get the value of defense
     */ 
    public function getDefense(): int
    {
        return $this->getModifiedValue(
            $this->defense, 
            Category::Defense, 
            0
        );
    }

    /**
     * Set the value of defense, min 0
     */ 
    public function setDefense(int $defense): void
    {
        if ($defense >= 0) {
            $this->defense = $defense;
        }
    }

    /**
     * Get the value of spellPower
     */ 
    public function getSpellPower(): int
    {
        return $this->getModifiedValue(
            $this->spellPower, 
            Category::SpellPower, 
            0
        );
    }

    /**
     * Set the value of spellPower, min 0
     */ 
    public function setSpellPower(int $spellPower): void
    {
        if ($spellPower >= 0) {
            $this->spellPower = $spellPower;
        }
    }

    /**
     * Get the value of health
     */ 
    public function getHealth()
    {
        return $this->health;
    }

    /**
     * Set the value of health, min 1
     */ 
    public function setHealth(int $health): void
    {
        if ($health > 0) {
            $this->health = $health;
            $this->maxHealth = $health;
        }
    }

    /**
     * Get the value of armor
     */ 
    public function getArmor()
    {
        $cap = $this->getModifiedValue($this->armorCap, Category::ArmorCap, 0, 90);
        return $this->getModifiedValue(
            $this->armor, 
            Category::Armor, 
            0, 
            $cap
        );
    }

    /**
     * Set the value of armor, min 0, max is at cap (default 75)
     */ 
    public function setArmor(int $armor): void
    {
        if ($armor >= 0 && $armor <= $this->armorCap) {
            $this->armor = $armor;
        }
    }

    /**
     * Get the value of ward
     */ 
    public function getWard()
    {
        $cap = $this->getModifiedValue($this->wardCap, Category::WardCap, 0, 90);
        return $this->getModifiedValue(
            $this->ward, 
            Category::Ward, 
            0, 
            $cap
        );
    }

    /**
     * Set the value of ward, min 0, max is at cap (default 75)
     */ 
    public function setWard(int $ward): void
    {
        if ($ward >= 0 && $ward <= $this->wardCap) {
            $this->ward = $ward;
        }
    }

    /**
     * Get the value of speed
     */ 
    public function getSpeed()
    {
        return $this->getModifiedValue(
            $this->speed, 
            Category::Speed, 
            0
        );
    }

    /**
     * Set the value of speed, min 1
     */ 
    public function setSpeed(int $speed): void
    {
        if ($speed > 0) {
            $this->speed = $speed;
        }
    }

    /**
     * Get the value of morale
     */ 
    public function getMorale()
    {
        return $this->morale;
    }

    /**
     * Set the value of morale, min 1000
     */ 
    public function setMorale(int $morale): void
    {
        if ($morale >= 5000) {
            $this->morale = $morale;
            $this->maxHealth = $morale;
        }
    }

    /**
     * Get the value of scriptName
     */ 
    public function getScriptName()
    {
        return $this->scriptName;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of icon
     */ 
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Get the value of threat
     */ 
    public function getThreat()
    {
        return $this->getModifiedValue(
            $this->threat, 
            Category::Threat, 
            0
        );
    }

    /**
     * Set the value of threat
     */ 
    public function setThreat(int $threat): void
    {
        if ($threat > 0) {
            $this->threat = $threat;
        }
    }

    public function applyModifier(Modifier $modifier): void
    {
        $stacks = $this->modifiers->add($modifier);

        $change = $stacks * $modifier->getStackValue();
        $category = $modifier->getCategory();
        if ($category === Category::Health) {
            $this->healthModifier($change);
        } elseif ($category === Category::Morale) {
            $this->moraleModifier($change);
        }

    }

    private function healthModifier(int $change): void
    {
        $this->maxHealth += $change;
        if ($this->maxHealth <= 0) {
            $this->maxHealth = 1;
        }
        if($this->health >= $this->maxHealth) {
            $this->health = $this->maxHealth;
        }
    }

    private function moraleModifier(int $change): void
    {
        $this->maxMorale += $change;
        if ($this->maxMorale <= 0) {
            $this->maxMorale = 1;
        }
        if($this->morale >= $this->maxMorale) {
            $this->morale = $this->maxMorale;
        }
    }

    public function dispellNegatives(Dispells $dispell): bool
    {
        return $this->modifiers->dispellNegatives($dispell);
    }
    
    public function dispellPositivess(Dispells $dispell): bool
    {
        return $this->modifiers->dispellNegatives($dispell);
    }

    public function getIgnoreArmor(): int
    {
        return $this->getModifiedValue(0, Category::IgnoreArmor, 0, 100);
    }

    public function getIgnoreWard(): int
    {
        return $this->getModifiedValue(0, Category::IgnoreWard, 0, 100);
    }

    /**
     * Makes unit take given amount of damage
     *
     * @param integer $damage amount to take
     * @return integer health lost, not include overkill
     */
    public function takeDamage(int $damage, School $school, Unit $source): int
    {
        if ($this->modifiers->haveDamageImmunity($school)) {
            return 0;
        }
        $damage = $this->calculateDamageMultiplier($damage, $school);
        if ($school === School::Physical) {
            $damage = $this->reduceDamageByArmor($damage, $source);
        } else {
            $damage = $this->reduceDamageByWard($damage, $source);

        }
        $damage = $this->limitDamage($damage);
        $healthLoss = min($this->health, $damage);
        $this->health -= $healthLoss;
        return $healthLoss;
    }

    private function calculateDamageMultiplier(int $damage, School $school): float
    {
        $multiplier = 1 + $this->modifiers->getTotalValue(Category::DamageTakenMultiplier, $school);
        return max(0, $multiplier * $damage);
    }

    private function reduceDamageByArmor(int $damage, Unit $source): float
    {
        $armor = $this->getArmor();
        $ignoreArmor = $source->getIgnoreArmor();
        $armor = ($ignoreArmor / 100) * $armor;
        return ($armor / 100) * $damage;
    }

    private function reduceDamageByWard(int $damage, Unit $source): float
    {
        $ward = $this->getWard();
        $ignoreWard = $source->getIgnoreWard();
        $ward = ($ignoreWard /100) * $ward;
        return ($ward /100) * $damage;
    }

    private function limitDamage(int $damage): int
    {
        $limitModifier = $this->modifiers->getTotalValue(Category::MaxDamageTaken);
        if ($limitModifier > 0) {
            $damage = min($damage, $limitModifier);
        }
        return $damage;
    }

    public function heal(int $heal): int
    {
        $multiplier = 1 + $this->modifiers->getTotalValue(Category::HealTakenMultiplier);
        if ($multiplier <= 0) {
            return 0;
        }
        $heal = $multiplier * $heal;
        $missingHealth = $this->maxHealth - $this->health;
        $heal = min($heal, $missingHealth);
        $this->health += $heal;
        return $heal;
    }

    public function damageMorale(int $strength = 1): void
    {
        $moraleDamage = $this->getModifiedValue(
            $this->moraleDamage, 
            Category::MoraleDamage, 
            1
        );
        $damage = $strength * $moraleDamage;
        $this->morale -= $damage;
    }

    public function addAbility(Ability $ability): void
    {
        $this->abilities->add($ability);
    }


    private function getModifiedValue(int $base, Category $category, ?float $min = null, ?float $max = null): float
    {
        $modifierValue = $this->modifiers->getTotalValue($category);
        $value = $base + $modifierValue;
        if($min !== null) {
            $value = max($min, $value);
        }
        if($max !== null) {
            $value = min($max, $value);
        }
        return $value;
    }
}