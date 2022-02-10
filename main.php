<?php

require_once 'vendor/autoload.php';


use VM\Helpers\Cli;
use VM\Helpers\InputHelper;
use VM\VendingMachine;


$acceptableCoins = [1, 2, 5, 10, 20, 50];
$acceptableProducts = ['A' => 0.95, 'B' => 1.26, 'C' => 2.33];

$obj = new VendingMachine(
    InputHelper::coinsToCollection($acceptableCoins),
    InputHelper::productsToCollection($acceptableProducts)
);

try {
    $obj
        ->setIOHandler(new Cli($argv))
        ->validateInput()
        ->processRequest()
        ->processOutput();
} catch (InvalidArgumentException|LogicException $e) {
    echo "--------------------------------------" . PHP_EOL;
    echo "Error: " . $e->getMessage() . PHP_EOL;
    exit;
}
