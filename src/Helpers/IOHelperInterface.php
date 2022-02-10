<?php

namespace VM\Helpers;


interface IOHelperInterface
{
    public function getCoins(): array;

    public function getProducts(): array;

    public function outputRest(array $coins): void;

}
