<?php

namespace App\Classes\Units\Abstracts;

abstract class UnitPattern
{
    private string $scriptName;

    private string $name;

    private string $icon;

    public function __construct()
    {
        $this->scriptName = $this->setScriptName();
        $this->name = $this->setName();
        $this->icon = $this->setIcon();
    }

    protected abstract function setScriptName(): string;

    protected abstract function setName(): string;

    protected abstract function setIcon(): string;

    protected abstract function pattern(UnitBuilder $builder): void;

    public function make(): Unit
    {
        $builder = new UnitBuilder(
            $this->scriptName, 
            $this->name, 
            $this->icon
        );
        $this->pattern($builder);
        return $builder->build();
    }
}