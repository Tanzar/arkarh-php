<?php

namespace App\Classes\Combat;

class ArmyPattern
{

    private array $reserve = [];

    private array $front = [];

    private array $back = [];

    public function __construct(array $reserve = [], array $front = [], array $back = [])
    {
        $this->reserve = $reserve;
        $this->front = $front;
        $this->back = $back;
    }

    
}