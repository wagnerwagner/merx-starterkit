<?php

use Wagnerwagner\Merx\Price;
use Wagnerwagner\Merx\PricingRule;

class ProductPage extends Wagnerwagner\Merx\ProductPage
{
	public function price(null|string|PricingRule $pricingRule = null, null|float $quantity = null): ?Price
	{
		$price = parent::price($pricingRule, $quantity);
		if ($quantity >= 5) {
			$price = $price->updatePrice($price->price * 0.98);
		}
		if ($quantity >= 10) {
			$price = $price->updatePrice($price->price * 0.96);
		}
		if ($quantity >= 15) {
			$price = $price->updatePrice($price->price * 0.94);
		}
		return $price;
	}

	public function maxQuantity(): float
	{
		if ($this->stock()->isNotEmpty() && $this->stock()->toFloat() < $this->site()->maxQuantity()->toFloat()) {
			return $this->stock()->toFloat();
		}
		return $this->site()->maxQuantity()->toFloat();
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
