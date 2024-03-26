<?php

return [
    'pattern' => 'shop/coupon',
    'auth' => false,
    'method' => 'POST',
    'action'  => function () {
        $code = Str::trim($this->requestBody('couponcode'));
        if (in_array($code, ['start5', 'starterkit5'])) {
            $cart = cart();
            $cart->add('coupon');
            return $this->cart();
        } else {
            kirby()->setCurrentTranslation($this->requestHeaders('x-language'));
            return [
                'status' => 'error',
                'code' => 400,
                'message' => t('error.merx.coupon.invalid'),
            ];
        }
    },
];
