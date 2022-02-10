<?php

namespace VM\Helpers;


use VM\Collection;
use VM\Item;


class InputHelper
{
    public static function coinsToCollection(array $coins): Collection
    {
        $collection = new Collection();
        foreach ($coins as $coin) {
            $collection->add(new Item((string)$coin, $coin));
        }

        return $collection;
    }

    public static function productsToCollection(array $products): Collection
    {
        $collection = new Collection();
        foreach ($products as $productKey => $productPrice) {
            $collection->add(new Item($productKey, (int)($productPrice * 100)));
        }

        return $collection;
    }
}
