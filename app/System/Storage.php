<?php

namespace App\System;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use ReflectionException;

class Storage
{
    /**
     * @var string
     */
    protected string $path;

    /**
     * @var string
     */
    protected string $className;
    /**
     * @var array
     */
    public array $data = [];

    /**
     * Storage constructor.
     *
     * @param string $className
     *
     * @throws ReflectionException
     */
    public function __construct(string $className)
    {
        $this->className = $className;
        $this->path = $this->generatePath($className);
        $this->load();
    }

    /**
     * @throws ReflectionException
     */
    protected function load(): void
    {
        foreach (readStorage($this->path) as $line) {
            $class = new $this->className();

            $reflector = new ReflectionMethod($class, 'load');
            $reflector->setAccessible(true);
            $reflector->invoke($class, unserialize($line, ['allowed_classes' => true]));

            $this->data[] = $class;
        }
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * @param int $id
     *
     * @return mixed|null
     */
    public function findById(int $id)
    {
        return $this->findByColumn('id', $id);
    }

    /**
     * @param $name
     * @param $value
     *
     * @return mixed|null
     */
    public function findByColumn($name, $value)
    {
        foreach ($this->data as $item) {
            if (isset($item->{$name}) && $item->{$name} === $value) {
                return $item;
            }
        }

        return null;
    }

    /**
     * @param $object
     *
     * @return bool
     * @throws ReflectionException
     */
    public function save($object): bool
    {
        if ($object->id !== null) {
            return $this->update($object);
        }

        return $this->insert($object);
    }

    /**
     * @param $object
     *
     * @return bool
     */
    protected function update($object): bool
    {
        foreach ($this->data as $key => $item) {
            if ($item->id === $object->id) {
                $this->data[$key] = $object;
            }
        }

        return $this->saveStorage();
    }

    /**
     * @param $object
     *
     * @return bool
     * @throws ReflectionException
     */
    protected function insert($object): bool
    {
        $ref = new ReflectionProperty($object, 'id');
        $ref->setAccessible(true);
        $ref->setValue($object, $this->getNextId());

        $this->data[] = $object;

        return $this->saveStorage();
    }

    /**
     * @return bool
     */
    protected function saveStorage(): bool
    {
        $tmp = array_map(static function ($object) {
            return serialize($object->toArray());
        },
            $this->data
        );

        return (bool)file_put_contents($this->path, implode("\n", $tmp));
    }

    /**
     * @return int
     */
    protected function getNextId(): int
    {
        $id = 0;

        foreach ($this->data as $item) {
            if ($item->id > $id) {
                $id = $item->id;
            }
        }

        return $id + 1;
    }

    /**
     * @param string $className
     *
     * @return string
     */
    protected function generatePath(string $className): string
    {
        return __DIR__ . '/../../storage/' . $className::TABLE . '.db';
    }
}
