<?php

namespace App\Classes\Units\Abstracts;

abstract class UnitPattern
{
    private string $scriptName;

    private string $name;

    private string $icon;

    private UnitBuilder $builder;

    public function __construct()
    {
        $this->scriptName = $this->setScriptName();
        $this->name = $this->setName();
        $this->icon = $this->setIcon();
        $this->builder = new UnitBuilder(
            $this->scriptName, 
            $this->name, 
            $this->icon
        );
        $this->pattern($this->builder);
    }

    protected abstract function setScriptName(): string;

    protected abstract function setName(): string;

    protected abstract function setIcon(): string;

    protected abstract function pattern(UnitBuilder $builder): void;

    public function make(): Unit
    {
        return $this->builder->build();
    }
}