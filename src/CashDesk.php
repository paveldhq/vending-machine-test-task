<?php

namespace VM;


class CashDesk
{
    public function __construct(
        protected Collection $coins
    ) {
    }

    public function getTotalCash()
    {
        $sum = 0;
        foreach ($this->coins->keys() as $key) {
            /**
             * @var \VM\Item $coin
             */
            $coin = $this->coins[$key];
            $sum += $coin->getPrice() * $coin->getStock();
        }

        return $sum;
    }

    /**
     * @param  Item[]  $coins
     *
     * @return float
     */
    public static function calculateAmount(array $coins): float
    {
        $amount = 0;
        foreach ($coins as $coin) {
            $amount += $coin->getPrice();
        }

        return $amount;
    }

    /**
     * @param  \VM\Item[]  $coins
     *
     * @return void
     */
    public function addCoins(array $coins): void
    {
        foreach ($coins as $coin) {
            $this->addCoin($coin);
        }
    }

    public function addCoin(Item $coin): void
    {
        /**
         * @var \VM\Item $currCoin
         */
        $currCoin = $this->coins[$coin->getKey()];
        $currCoin->setStock($currCoin->setStock() + 1);
    }
}
