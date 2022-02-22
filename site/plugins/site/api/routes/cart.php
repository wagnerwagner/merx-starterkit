<?php

return [
    'pattern' => 'shop/cart',
    'auth' => false,
    'action' => function () {
        return $this->cart();
    },
];
