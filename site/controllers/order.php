<?php

use Kirby\Toolkit\Str;

return function (OrderPage $page) {
	$cart = $page->cart();
	// The label of the payment method the customer chose in the checkout
	$paymentMethod = collection('payment-methods')->findBy('value', $page->paymentMethod()->value())['label'] ?? '';

	// Merx keeps what the payment provider reports about a payment,
	// e.g. the brand of the credit card: “Credit Card (Visa)”
	if ($cardBrand = $page->paymentDetails()['card_brand'] ?? null) {
		$paymentMethod .= ' (' . Str::ucfirst($cardBrand) . ')';
	}

	return [
		'paymentMethod' => $paymentMethod,
		// The amount to pay: the gross total, also when the prices of the
		// order are net prices, as in the USA
		'total' => $cart->total()->toString('price'),
	];
};
