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

    public function getReserve(): array
    {
        return $this->reserve;
    }

    public function getBack(): array
    {
        return $this->back;
    }

    public function getFront(): array
    {
        return $this->front;
    }
}