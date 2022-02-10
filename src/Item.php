<?php

namespace VM;


use InvalidArgumentException;


class Item
{
    public function __construct(
        protected string $key,
        protected int $price = 0,
        protected int $stock = 1000
    ) {
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function checkAvailability(int $items): bool
    {
        return $this->stock >= $items;
    }

    public function setStock(int $stock): void
    {
        if ($stock < 0) {
            throw new InvalidArgumentException('Stock cannot be < 0');
        }
        $this->stock = $stock;
    }
}
