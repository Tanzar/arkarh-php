<?php

namespace App\Classes\Abilities\Shared;

use Illuminate\Support\Collection;

class Abilities
{
    private Collection $abilities;

    public function __construct()
    {
        $this->abilities = new Collection();
        $triggers = Trigger::cases();
        foreach ($triggers as $trigger) {
            $this->abilities->put($trigger->value, new Collection());
        }
    }

    public function add(Ability $ability): void
    {
        $trigger = $ability->getTrigger();
        $abilities = $this->abilities->get($trigger->value);
        $abilities->put($trigger->value, $ability);
    }

    public function act(Trigger $trigger): void
    {
        $abilities = $this->abilities->get($trigger->value);
        /** @var Ability $ability */
        foreach ($abilities as $ability) {
            $ability->act();
        }
    }
}