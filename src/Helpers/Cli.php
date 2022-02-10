<?php

namespace VM\Helpers;


use VM\Item;


class Cli implements IOHelperInterface
{

    private array $params;

    public function __construct($args)
    {
        $this->params = array_slice($args, 1);
    }

    /**
     * @return Item[]
     */
    public function getCoins(): array
    {
        return array_map(
            fn($element) => new Item((string)$element, $element, 1),
            array_slice($this->params, 0, count($this->params) - 1)
        );
    }

    public function getProducts(): array
    {
        return [new Item(end($this->params))];
    }

    public function outputRest(array $coins): void
    {
        foreach ($coins as $coin) {
            /**
             * @var Item $coin
             */
            echo str_repeat(
                vsprintf(
                    '%s ',
                    [
                        $coin->getPrice(),
                    ]
                ),
                $coin->getStock()
            );
        }
        echo PHP_EOL;
    }
}
