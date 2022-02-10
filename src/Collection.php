<?php

namespace VM;


use ArrayAccess;


class Collection implements ArrayAccess
{
    protected array $storage = [];

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($this->normalizeKey($offset), $this->storage);
    }

    public function offsetGet(mixed $offset): mixed
    {
        $offset = $this->normalizeKey($offset);
        if (isset($this[$offset])) {
            return $this->storage[$this->normalizeKey($offset)];
        }
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($value instanceof Item) {
            $this->storage[$this->normalizeKey($offset)] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        if (isset($this[$offset])) {
            unset($this->storage[$this->normalizeKey($offset)]);
        }
    }

    public function add(Item $product)
    {
        $this[$product->getKey()] = $product;
    }

    protected function normalizeKey(mixed $key): string
    {
        return strtoupper((string)$key);
    }

    public function keys(): array
    {
        return array_keys($this->storage);
    }
}
