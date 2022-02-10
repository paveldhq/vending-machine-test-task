<?php

class VMachine {

    protected $acceptableCoins;
    protected $acceptableProducts;


    protected $coins;
    protected $requestedProduct;

    protected function validateCoin($coin) {
        return in_array($coin, $this->acceptableCoins, true);
    }

    protected function validateCoins(array $inputCoins) {
        $result = true;
        foreach ($inputCoins as $coin) {
            $result &= $this->validateCoin($coin);
            if (!$result) {
                break;
            }
        }
        return $result;
    }

    protected function validateProduct($product) {
        return in_array($product, array_keys($this->acceptableProducts), true);
    }

    public function __construct(array $acceptableCoins, array $acceptableProducts) {
        $this->acceptableCoins = $acceptableCoins;
        $this->acceptableProducts = $acceptableProducts;
    }

    protected function calculateInputAmount(array $coins) {
        $amount = 0;
        foreach ($coins as $coin) {
            $amount += $coin;
        }
        return $amount;
    }

    protected function calculateRest($inputAmount, $product) {
        $productPrice = $this->acceptableProducts[$product] * 100;
        return $inputAmount - $productPrice;
    }

    public function handleInput(array $data) {
        $this->coins = array_map(
            function($element){ return (int) $element; },
            array_slice($data, 0, count($data) - 1)
        );

        $this->requestedProduct = strtoupper(end($data));
        if (!$this->validateCoins($this->coins)) {
            throw new \Exception('Bad coin');
        }

        if (!$this->validateProduct($this->requestedProduct)) {
            throw new \Exception('Bad Product');
        }
        return $this;
    }

    protected function calculateRestCoins($amount) {

        $coins = $this->acceptableCoins;
        rsort($coins);

        $rest = [];

        foreach ($coins as $coinAmount) {
            $pRest = $amount % $coinAmount;
            $pCoins = floor($amount/$coinAmount);
            if ($pCoins > 0) {
                $rest[$coinAmount] = $pCoins;
            }
            $amount=$pRest;
        }
        return $rest;
    }

    public function processRequest() {
        $inputAmount = $this->calculateInputAmount($this->coins);
        $rest = $this->calculateRest($inputAmount, $this->requestedProduct);
        $restOut = [];

        if ($rest < 0) {
            throw new \Exception('Need more money.');
        }
        if ($rest > 0) {
            $restOut = $this->calculateRestCoins($rest);
        }
        return $restOut;
    }


}

$acceptableCoins = [1, 2, 5, 10, 20, 50];
$acceptableProducts = ['A' => 0.95, 'B'=> 1.26, 'C'=> 2.33];
$params = array_slice($argv, 1);

$vMachine = new VMachine($acceptableCoins, $acceptableProducts);
$rest = $vMachine
    ->handleInput($params)
    ->processRequest();

var_dump($rest);
