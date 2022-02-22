<?php

return [
    'pattern' => 'shop/checkout',
    'auth' => false,
    'method' => 'POST',
    'action' => function () {
        $data = $_POST;
        $paymentIntentId = kirby()->session()->get('ww.site.paymentIntentId', '');
        $data = array_merge($data, [
            'stripePaymentIntentId' => $paymentIntentId,
        ]);
        $redirect = merx()->initializePayment($data);
        return [
            'status' => 201,
            'redirect' => $redirect,
        ];
    },
];
