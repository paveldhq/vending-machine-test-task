<?php

namespace VM;


class Validator
{
    public function __construct(private Collection $collection)
    {
    }

    protected function validateItem(Item $item): bool
    {
        return isset($this->collection[$item->getKey()]);
    }

    public function validate(array $items): bool
    {
        foreach ($items as $item) {
            if (!$this->validateItem($item)) {
                return false;
            }
        }

        return true;
    }
}
