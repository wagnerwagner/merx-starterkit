<?php

class ShippingPage extends Page
{
    public function tax(): float
    {
        if ($this->content()->tax()->isEmpty()) {
            return 0;
        }
        return $this->kirby()->option('taxRates')[$this->content()->tax()->toString()];
    }
}
