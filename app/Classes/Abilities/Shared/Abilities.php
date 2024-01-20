<?php

namespace App\Classes\Abilities\Shared;

use Illuminate\Support\Collection;

class Abilities
{
    private Collection $abilities;

    private Collection $scriptNames;

    public function __construct()
    {
        $this->abilities = new Collection();
        $this->scriptNames = new Collection();
        $triggers = Trigger::cases();
        foreach ($triggers as $trigger) {
            $this->abilities->put($trigger->value, new Collection());
        }
    }

    public function add(string $scriptName, Ability $ability): void
    {
        if (!$this->scriptNames->has($scriptName)) {
            $trigger = $ability->getTrigger();
            $abilities = $this->abilities->get($trigger->value);
            $abilities->put($scriptName, $ability);
            $this->scriptNames->put($scriptName, $trigger);
        }
    }

    public function act(Trigger $trigger): void
    {
        $abilities = $this->abilities->get($trigger->value);
        /** @var Ability $ability */
        foreach ($abilities as $ability) {
            $ability->act();
        }
    }

    public function get(string $scriptName): ?Ability
    {
        if ($this->scriptNames->has($scriptName)) {
            $trigger = $this->scriptNames->get($scriptName);
            $abilities = $this->abilities->get($trigger->value);
            return $abilities->get($scriptName);
        }
        return null;
    }
}