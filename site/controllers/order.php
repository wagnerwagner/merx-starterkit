<?php

use Kirby\Toolkit\Str;

return function (OrderPage $page) {
    $cart = $page->cart();
    $paymentMethod = Str::camel($page->paymentMethod()->toString());

    return [
        'paymentMethod' => t('paymentMethod.' . $paymentMethod . '.label'),
        'sum' => formatPrice($cart->getSum()),
    ];
};
