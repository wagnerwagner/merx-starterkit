<?php

use Wagnerwagner\Merx\ProductPage;

class ShippingPage extends ProductPage
{
    public function taxRate(): float
    {
        if ($this->content()->tax()->isEmpty()) {
            return 0;
        }
        return $this->kirby()->option('taxRates')[$this->content()->tax()->toString()] / 100;
    }
}
