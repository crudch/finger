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

    public function __construct()
    {
        if (property_exists(static::class, 'date')) {
            $this->date = new AppDate();
        }
    }

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
     * @param string|AppDate $date
     *
     * @return AppDate
     * @throws Exception
     */
    public function setDate($date): AppDate
    {
        if ($date instanceof AppDate) {
            return $date;
        }

        if (!is_string($date)) {
            throw new InvalidArgumentException('Некорректная передача данных');
        }

        return new AppDate($date);
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
     * @throws ReflectionException
     */
    public function save(): bool
    {
        $storage = new Storage(static::class);

        return $storage->save($this);
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public static function __callStatic($name, $arguments)
    {
        $storage = new Storage(static::class);

        if (method_exists($storage, $name)) {
            return $storage->$name(...$arguments);
        }

        throw new BadMethodCallException("Вызван несуществующий метод {$name}");
    }

    final protected function load(array $data): void
    {
        foreach ($data as $name => $value) {
            'id' === $name ? $this->id = $value : $this->__set($name, $value);
        }
    }
}
