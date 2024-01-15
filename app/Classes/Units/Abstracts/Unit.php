<?php

namespace App\Classes\Units\Abstracts;

use App\Classes\Abilities\Shared\Abilities;
use App\Classes\Abilities\Shared\Ability;
use App\Classes\Abilities\Shared\Trigger;
use App\Classes\Combat\CombatLog;
use App\Classes\Modifiers\Category;
use App\Classes\Modifiers\Modifier;
use App\Classes\Modifiers\Modifiers;
use App\Classes\Shared\Types\Dispells;
use App\Classes\Shared\Types\School;
use App\Classes\Shared\Utility\IdGenerator;
use App\Classes\Tag\Unit\Tag;
use App\Classes\Tag\Unit\Tags;
use App\Classes\Units\Escape\Standard;

class Unit 
{
    private int $id;

    private int $typeId;

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

    private bool $prefersFront = false;

    private Modifiers $modifiers;

    private Abilities $abilities;

    private Tags $tags;

    private bool $isAttacker = false;

    private EscapeStrategy $escapeStrategy;

    private int $position = -1;

    public function __construct(int $id, string $name, string $icon)
    {
        $this->typeId = $id;
        $this->id = IdGenerator::get();
        $this->name = $name;
        $this->icon = $icon;
        $this->modifiers = new Modifiers();
        $this->abilities = new Abilities();
        $this->tags = new Tags();
        $this->escapeStrategy = new Standard();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTypeId(): int
    {
        return $this->typeId;
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
     * Get the value of maxMorale
     */ 
    public function getMaxMorale()
    {
        return $this->maxMorale;
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

    public function prefersFront(?bool $prefersFront = null): bool
    {
        if ($prefersFront !== null) {
            $this->prefersFront = $prefersFront;
        }
        return $this->prefersFront;
    }

    public function prefersBack(?bool $prefersBack = null): bool
    {
        if ($prefersBack !== null) {
            $this->prefersFront = !$prefersBack;
        }
        return !$this->prefersFront;
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
        $this->act(Trigger::Dispell);
        return $this->modifiers->dispellNegatives($dispell);
    }
    
    public function dispellPositivess(Dispells $dispell): bool
    {
        $this->act(Trigger::Dispell);
        return $this->modifiers->dispellNegatives($dispell);
    }

    /**
     * Makes unit take given amount of damage
     *
     * @param integer $damage amount to take
     * @return integer health lost, not include overkill
     */
    public function takeDamage(int $damage, School $school, bool $piercing): int
    {
        if ($this->isAlive()) {
            if ($this->modifiers->haveDamageImmunity($school)) {
                return 0;
            }
            $damage = $this->calculateDamageMultiplier($damage, $school);
            if ($school === School::Physical) {
                $damage = $this->reduceDamageByArmor($damage, $piercing);
            } else {
                $damage = $this->reduceDamageByWard($damage, $piercing);

            }
            $damage = $this->limitDamage($damage);
            $healthLoss = min($this->health, $damage);
            if ($healthLoss > 0) {
                $this->act(Trigger::DamageTake);
            }
            $this->health -= $healthLoss;
            if ($this->isDead()) {
                $this->act(Trigger::Death);
            }
            return $healthLoss;
        }
        return 0;
    }

    private function calculateDamageMultiplier(int $damage, School $school): float
    {
        $multiplier = 1 + (-1 * $this->modifiers->getTotalValue(Category::DamageTakenMultiplier, $school));
        return max(0, $multiplier * $damage);
    }

    private function reduceDamageByArmor(int $damage, bool $piercing): float
    {
        if ($piercing && !$this->modifiers->isHardened()) {
            return $damage;
        } else {
            $armor = $this->getArmor();
            $multiplier = 1 - ($armor / 100);
            return $damage * $multiplier;
        }
    }

    private function reduceDamageByWard(int $damage, bool $piercing): float
    {
        if ($piercing && !$this->modifiers->isHardened()) {
            return $damage;
        } else {
            $ward = $this->getWard();
            $multiplier = 1 - ($ward / 100);
            return $damage * $multiplier;
        }
    }

    private function limitDamage(int $damage): int
    {
        $limitModifier = $this->modifiers->getTotalValue(Category::MaxDamageTaken);
        if ($limitModifier > 0) {
            $damage = min($damage, $limitModifier);
        }
        return $damage;
    }

    /**
     * Attempt to heal unit for given amount
     *
     * @param integer $heal amount of health to heal target for
     * @return integer amount of health restored
     */
    public function heal(int $heal): int
    {
        if ($this->isAlive()) {
            $multiplier = 1 + $this->modifiers->getTotalValue(Category::HealTakenMultiplier);
            if ($multiplier <= 0) {
                return 0;
            }
            $heal = $multiplier * $heal;
            $missingHealth = $this->maxHealth - $this->health;
            $heal = min($heal, $missingHealth);
            if ($heal > 0) {
                $this->act(Trigger::Heal);
            }
            $this->health += $heal;
            return $heal;
        }
        return 0;
    }

    public function damageMorale(int $strength = 1): void
    {
        $moraleDamage = -1 * $this->getModifiedValue(
            $this->moraleDamage, 
            Category::MoraleDamage, 
            1
        );
        $damage = $strength * $moraleDamage;
        $this->morale += $damage;
    }

    public function increaseMorale(int $value): void
    {
        if ($value > 0 && $this->maxMorale >= $this->morale) {
            $this->morale += $value;
        }
    }

    public function addAbility(Ability $ability): void
    {
        $this->abilities->add($ability);
    }

    public function addTag(Tag $tag): void
    {
        $this->tags->add($tag);
        $modifiers = $tag->getModifiers();
        foreach ($modifiers as $modifier) {
            $this->modifiers->add($modifier);
        }
    }

    public function setAttacker(): void
    {
        $this->isAttacker = true;
    }

    public function isAttacker(): bool
    {
        return $this->isAttacker;
    }

    public function setDefender(): void
    {
        $this->isAttacker = false;
    }

    public function isDefender(): bool
    {
        return !$this->isAttacker;
    }

    public function isDead(): bool
    {
        return $this->getHealth() <= 0;
    }

    public function isAlive(): bool
    {
        return !$this->isDead();
    }

    public function canFight(bool $onReserve): bool
    {
        return $this->escapeStrategy->canFight($this, $onReserve);
    }

    public function act(Trigger $trigger = Trigger::Action): void
    {
        if ($this->isAlive()) {
            $this->triggerHealOverTime();
            if ($this->isAlive()) {
                $this->abilities->act($trigger);
            }
        }
    }

    private function triggerHealOverTime(): void
    {
        $hots = $this->modifiers->getModifiers(Category::HealOverTime);
        foreach ($hots as $modifier) {
            $value = $modifier->getTotalValue();
            $healingTaken = $this->heal($value);
            CombatLog::getInstance()->nextStage();
            CombatLog::getInstance()->addState($this, $this->name . ' heals for ' . $healingTaken . '.');
        }
    }

    public function getModifiedValue(int $base, Category $category, ?float $min = null, ?float $max = null): float
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

    /**
     * Set the value of escapeStrategy
     *
     * @return  self
     */ 
    public function setEscapeStrategy(EscapeStrategy $escapeStrategy): void
    {
        $this->escapeStrategy = $escapeStrategy;
    }

    /**
     * Get the value of position
     */ 
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * Set the value of position
     *
     * @return  self
     */ 
    public function setPosition($position): void
    {
        $this->position = $position;
    }
}