<?php

class ProductPage extends Wagnerwagner\Merx\ProductPage
{
    public function maxAmount(): float
    {
        if ($this->stock()->isNotEmpty() && $this->stock()->toFloat() < $this->site()->maxAmount()->toFloat()) {
            return $this->stock()->toFloat();
        }
        return $this->site()->maxAmount()->toFloat();
    }

    public function taxRate(): float
    {
        if ($this->content()->tax()->isEmpty()) {
            return 0;
        }
        return $this->kirby()->option('taxRates')[$this->content()->tax()->toString()] / 100;
    }

    public function stockInfo(): ?string
    {
        $stockInfo = null;
        $stock = $this->stock()->isNotEmpty() ? $this->stock()->toInt() : null;
        $productTitle = $this->title();
        if ($stock !== null) {
            if ($stock === 0) {
                $stockInfo = tt('product.stock.sold-out', compact('productTitle'));
            } else if ($stock === 1) {
                $stockInfo = tt('product.stock.info.1', compact('productTitle'));
            } else if ($stock <= 5) {
                $stockInfo = tt('product.stock.info', compact('productTitle', 'stock'));
            }
        }
        return $stockInfo;
    }
}
