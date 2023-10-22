<?php

namespace App\Classes\Combat;
use Illuminate\Support\Collection;

class Side
{
    private Collection $front;
    
    private Collection $back;

    private Collection $graveyard;

    private Collection $reserves;

    public function __construct(ArmyPatterns $armies)
    {
        $this->front = new Collection();
        $this->back = new Collection();
        $this->graveyard = new Collection();
        $this->reserves = new Collection();
    }

    private function positionUnits(ArmyPatterns $armies): void
    {
        /** @var ArmyPattern $army */
        foreach ($armies->getPatterns() as $army) {
            
        }
    }


}