<?php

namespace App\Classes\Modifiers;

use App\Classes\Shared\Types\Dispells;
use App\Classes\Shared\Types\School;
use Illuminate\Support\Collection;

class Modifiers
{
    private Collection $modifiers;

    public function __construct()
    {
        $this->modifiers = new Collection();
        $types = Category::cases();
        foreach ($types as $type) {
            $this->modifiers->put($type->value, new Collection());
        }
    }

    public function add(Modifier $modifier): int
    {
        $category = $modifier->getCategory();
        $mods = $this->getModifiers($category);
        /** @var Modifier $mod */
        foreach ($mods as $mod) {
            if ($mod->areSame($modifier) && $mod->canChangeOnApply()) {
                return $mod->changeStacks();
            }
        }
        $mods->push($modifier);
        return $modifier->getStacks();
    }

    public function update(): void
    {
        foreach ($this->modifiers as $mods) {
            $this->updateModifiers($mods);
        }
    }

    private function updateModifiers(Collection $mods): void
    {
        /** @var Modifier $mod */
        foreach ($mods as $key => $mod) {
            $mod->reduceDuration();
            if ($mod->shouldRemove()) {
                $mods->forget($key);
            }
        }

    }
    
    public function getModifiers(Category $category): Collection
    {
        return $this->modifiers->get($category->value);
    }

    public function dispellNegatives(Dispells $dispell): bool
    {
        $dispelled = false;
        foreach ($this->modifiers as $mods) {
            /** @var Modifier $modifier */
            foreach ($mods as $key => $modifier) {
                if (
                    $modifier->canDispell($dispell) && 
                    $modifier->isNegative()
                ) {
                    $mods->forget($key);
                    $dispelled = true;
                }
            }
        }
        return $dispelled;
    }

    public function dispellPositives(Dispells $dispell): bool
    {
        $dispelled = false;
        foreach ($this->modifiers as $mods) {
            /** @var Modifier $modifier */
            foreach ($mods as $key => $modifier) {
                if (
                    $modifier->canDispell($dispell) && 
                    $modifier->isPositive()
                ) {
                    $mods->forget($key);
                    $dispelled = true;
                }
            }
        }
        return $dispelled;
    }

    public function getTotalValue(Category $category, School $school = School::Uncategorized): float
    {
        $sum = 0;
        $modifiers = $this->getModifiers($category);
        /** @var Modifier $modifier */
        foreach ($modifiers as $modifier) {
            if ($school === School::Uncategorized || $modifier->getSchool() === $school) {
                $sum += $modifier->getTotalValue();
            }
        }
        return $sum;
    }

    public function haveDamageImmunity(School $school): bool
    {
        if ($school === School::Uncategorized) {
            return false;
        }
        $modifiers = $this->getModifiers(Category::DamageImmunity);
        /** @var Modifier $modifier */
        foreach ($modifiers as $modifier) {
            if ($modifier->getSchool() === $school) {
                return true;
            }
        }
        return false;
    }

    public function isHardened(): bool
    {
        $modifiers = $this->getModifiers(Category::Hardened);
        return $modifiers->count() > 0;
    }

}