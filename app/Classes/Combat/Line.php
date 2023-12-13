<?php

namespace App\Classes\Combat;

use App\Classes\Units\Abstracts\Unit;

class Line implements \Iterator
{
    private array $units = [];
    
    private int $position;

    private int $index;

    private int $startPosition;

    private int $width;

    private bool $isFront;

    public function __construct(int $width, bool $isFront)
    {
        $this->units = [];
        $this->startPosition = floor($width / 2);
        $this->position = floor($width / 2);
        $this->index = 0;
        $this->width = $width;
        $this->isFront = $isFront;
        for ($i = 0; $i < $width; $i++) {
            $this->units[$i] = null;
        }
    }

    public function add(int $position, Unit $unit): void
    {
        if ($position >= 0 && $position < $this->width) {
            $this->units[$position] = $unit;
            $unit->setPosition($position);
        }
    }

    public function get(int $position): ?Unit
    {
        return $this->units[$position] ?? null;
    }

    public function remove(int $position): void
    {
        if (isset($this->units[$position]) && $this->units[$position] !== null) {
            $this->units[$position]->setPosition(-1);
            $this->units[$position] = null;
        }
    }

    public function isFront(): bool
    {
        return $this->isFront;
    }

    public function isBack(): bool
    {
        return !$this->isFront;
    }

    public function current(): ?Unit
    {
        return $this->units[$this->position];
    }

    public function next(): void
    {
        $this->index++;
        $pos = $this->startPosition + ceil($this->index / 2) * (($this->index % 2) ? -1 : 1 );
        $this->position = $pos;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return $this->index < $this->width;
    }

    public function rewind(): void
    {
        $this->index = 0;
        $this->position = $this->startPosition;
    }

}