<?php

namespace App\Classes\Units\Abstracts;

abstract class UnitPattern
{
    private string $name;

    private string $icon;

    private UnitBuilder $builder;

    public function __construct()
    {
        $this->name = $this->setName();
        $this->icon = $this->setIcon();
        $this->builder = new UnitBuilder(
            $this->name, 
            $this->icon
        );
        $this->pattern($this->builder);
    }

    protected abstract function setName(): string;

    protected abstract function setIcon(): string;

    protected abstract function pattern(UnitBuilder $builder): void;

    public function make(): Unit
    {
        return $this->builder->build();
    }
}