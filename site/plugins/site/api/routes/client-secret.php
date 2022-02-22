<?php

return [
    'pattern' => 'shop/client-secret',
    'auth' => false,
    'action' => function () {
        $cart = cart();
        $paymentIntent = $cart->getStripePaymentIntent();
        kirby()->session()->set('ww.site.paymentIntentId', $paymentIntent->id);
        return [
            'clientSecret' => $paymentIntent->client_secret,
        ];
    },
];
