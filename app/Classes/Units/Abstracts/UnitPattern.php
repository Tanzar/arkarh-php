<?php

namespace App\Classes\Units\Abstracts;

abstract class UnitPattern
{
    private UnitBuilder $builder;

    private static array $instances = [];

    protected abstract function __construct();

    public static function getInstance(): self
    {
        $calledClass = get_called_class();
        if (!isset(self::$instances[$calledClass])) {
            $instance = new $calledClass();
            $instance->builder = new UnitBuilder(
                $instance->setName(), 
                $instance->setIcon()
            );
            $instance->pattern($instance->builder);
            self::$instances[$calledClass] = $instance;
        }
        return self::$instances[$calledClass];
    }

    protected abstract function setName(): string;

    protected abstract function setIcon(): string;

    protected abstract function pattern(UnitBuilder $builder): void;

    public function make(): Unit
    {
        return $this->builder->build();
    }
}