<?php

namespace App\System;

use Exception;
use ReflectionException;
use BadMethodCallException;
use InvalidArgumentException;

/**
 * Class Model
 *
 * @package App\System
 *
 * @method static Model[] all()
 * @method static Model findById(int $id)
 */
class Model
{
    /**
     * @var int
     */
    protected ?int $id = null;


    /**
     * @var string
     */
    public static string $table = '';


    public function __isset($name)
    {
        return isset($this->{$name});
    }

    public function __set($name, $value)
    {
        if ('id' !== $name && isset($this->{$name})) {
            $this->{$name} = method_exists($this, $method = 'set' . ucfirst($name)) ? $this->$method($value) : $value;
        }
    }

    public function __get($name)
    {
        if (!isset($this->{$name})) {
            return null;
        }

        if (method_exists($this, $method = 'get' . ucfirst($name))) {
            return $this->$method();
        }

        return $this->{$name};
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function fill(array $data): self
    {
        foreach ($data as $name => $value) {
            $this->__set($name, $value);
        }

        return $this;
    }

    /**
     * Сохраняет модель
     *
     * @return bool
     */
    public function save(): bool
    {
        $storage = new Storage(static::class);

        return $storage->save($this);
    }

    public static function __callStatic($name, $arguments)
    {
        $storage = new Storage(static::class);

        if (method_exists($storage, $name)) {
            return $storage->$name(...$arguments);
        }

        throw new BadMethodCallException("Вызван несуществующий метод {$name}");
    }
}
