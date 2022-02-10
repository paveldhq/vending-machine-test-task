<?php

namespace VM;


use LogicException;
use VM\Helpers\IOHelperInterface;


class VendingMachine
{
    private array $rest;

    protected Validator $coinValidator;
    protected Validator $productValidator;
    protected CashDesk $cashDesk;

    private IOHelperInterface $IOHelper;

    public function __construct(
        private Collection $coins,
        private Collection $products,
    ) {
        $this->coinValidator = new Validator($this->coins);
        $this->productValidator = new Validator($this->products);
        $this->cashDesk = new CashDesk($this->coins);
    }

    protected function calculateInputAmount(array $coins): float
    {
        return CashDesk::calculateAmount($coins);
    }

    public function setIOHandler(IOHelperInterface $IOHelper): static
    {
        $this->IOHelper = $IOHelper;

        return $this;
    }

    public function validateInput(): static
    {
        $insertedCoins = $this->IOHelper->getCoins();
        $requestedProducts = $this->IOHelper->getProducts();

        if (!$this->coinValidator->validate($insertedCoins)) {
            throw new \InvalidArgumentException('Bad coin');
        }

        if (!$this->productValidator->validate($requestedProducts)) {
            throw new \InvalidArgumentException('Bad Product');
        }

        return $this;
    }

    public function processOutput(): void
    {
        $this->IOHelper->outputRest($this->rest);
    }

    protected function calculateRestCoins(float $amount): array
    {
        $coinKeys = $this->coins->keys();
        rsort($coinKeys);

        $rest = [];
        foreach ($coinKeys as $coinKey) {
            $coinPriceCoins = $this->coins[$coinKey]->getPrice();
            $pRest = $amount % $coinPriceCoins;
            $pCoins = floor($amount / $coinPriceCoins);
            if ($pCoins > 0) {
                $rest[$coinKey] = new Item((string)$coinKey, $this->coins[$coinKey]->getPrice(), $pCoins);
            }
            $amount = $pRest;
        }

        return $rest;
    }

    public function processRequest(): static
    {
        $inputAmount = $this->calculateInputAmount($this->IOHelper->getCoins());
        /**
         * @var \VM\Item $requestedProduct
         */
        $requestedProduct = $this->products[$this->IOHelper->getProducts()[0]->getKey()];
        if ($inputAmount < $requestedProduct->getPrice()) {
            throw new LogicException(
                vsprintf(
                    'Not enough money. You gave: $%s, but $%s required.',
                    [
                        $inputAmount / 100,
                        $requestedProduct->getPrice() / 100,
                    ]
                )
            );
        }

        $this->rest = $this->calculateRestCoins($inputAmount - $requestedProduct->getPrice());

        return $this;
    }

}
