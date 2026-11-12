<?php

use Wagnerwagner\Merx\Price;
use Wagnerwagner\Merx\PricingRule;

class ProductPage extends Wagnerwagner\Merx\ProductPage
{
	public function price(string|PricingRule|null $pricingRule = null, float|null $quantity = null, array|null $data = null): Price|null
	{
		$price = parent::price($pricingRule, $quantity, $data);
		// A product without a price for the pricing rule, e.g. the clock in the USA
		if ($price === null) {
			return null;
		}
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

	public function stockInfo(): ?string
	{
		$stockInfo = null;
		$stock = $this->stock();
		$productTitle = $this->title();
		if ($stock !== null) {
			if ($stock === 0.0) {
				$stockInfo = tt('product.stock.sold-out', compact('productTitle'));
			} else if ($stock === 1.0) {
				$stockInfo = tt('product.stock.info.1', compact('productTitle'));
			} else if ($stock <= 5.0) {
				$stockInfo = tt('product.stock.info', compact('productTitle', 'stock'));
			}
		}
		return $stockInfo;
	}
}
