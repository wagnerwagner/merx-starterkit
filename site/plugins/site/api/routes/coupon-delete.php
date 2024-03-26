<?php

return [
    'pattern' => 'shop/coupon',
    'auth' => false,
    'method' => 'DELETE',
    'action'  => function () {
        $cart = cart();
        $cart->remove('coupon');
        return $this->cart();
    },
];
