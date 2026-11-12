<?php

use Kirby\Toolkit\Str;

return function (OrderPage $page) {
	$cart = $page->cart();
	$paymentMethod = Str::camel($page->paymentMethod()->toString());

	return [
		'paymentMethod' => t('paymentMethod.' . $paymentMethod . '.label'),
		// The amount to pay: the gross total, also when the prices of the
		// order are net prices, as in the USA
		'total' => $cart->total()->toString('price'),
	];
};
