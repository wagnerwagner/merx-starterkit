<?php
return function (OrderPage $page) {
    $cart = $page->cart();

    return [
        'paymentMethod' => t('paymentMethod.' . $page->paymentMethod()->toString() . '.label'),
        'sum' => formatPrice($cart->getSum()),
    ];
};
