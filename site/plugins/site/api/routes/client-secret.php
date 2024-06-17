<?php

use Kirby\Cms\Api;

return [
    'pattern' => 'shop/client-secret',
    'auth' => false,
    'action' => function () {
        /** @var Api $this */
        $paymentMethod = $this->requestQuery('payment-method', 'card');
        $params = [
            'payment_method_types' => [$paymentMethod],
        ];

        if ($paymentMethod === 'sepa_debit') {
            $params['capture_method'] = 'automatic';
        }

        $cart = cart();
        $paymentIntent = $cart->getStripePaymentIntent($params);
        kirby()->session()->set('ww.site.paymentIntentId', $paymentIntent->id);
        return [
            'clientSecret' => $paymentIntent->client_secret,
        ];
    },
];
