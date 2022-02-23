<?php

return [
    'languages' => true,
    'thumbs' => [
        'quality' => 80,
        'presets' => [
            'default' => ['width' => 704],
            'thumb' => ['width' => 46, 'height' => 46, 'crop' => true],
        ],
    ],

    /**
     * This array is used to generate the tax field (in percent) for every product.
     * Used in
     * /site/blueprints/fields/product-standards.yml
     * /site/models/product.php
     * /site/models/product-variant.php
     */
    'taxRates' => [
        'reduced' => 7,
        'default' => 19,
    ],

    /**
     * You MUST add sandbox/test keys to make the shop work with Stripe and/or PayPal.
     * For production mode (to make real payments) you have to add the live keys,
     * set production to true and add the Merx license.
     *
     * You can add custom keys for specific domains. The Starterkit demo has a custom
     * config file (config.starterkit.merx.wagnerwagner.de.php) the its secret keys.
     * More information: https://getkirby.com/docs/guide/configuration#multi-environment-setup
     */
    'ww.merx.stripe.test.publishable_key' => '',
    'ww.merx.stripe.test.secret_key' => '',
    'ww.merx.paypal.sandbox.clientID' => '',
    'ww.merx.paypal.sandbox.secret' => '',

    'ww.merx.stripe.live.publishable_key' => '',
    'ww.merx.stripe.live.secret_key' => '',
    'ww.merx.paypal.live.clientID' => '',
    'ww.merx.paypal.live.secret' => '',

    'ww.merx.production' => false,
    'ww.merx.license' => 'MERX-XXXXXXXX-XXXXXXXX',

    /**
     * A custom payment gateway (payment method). For the prepayment gateway no additional action is required.
     * The user will directly be redirected to the order page.
     * https://merx.wagnerwagner.de/docs/options#gateways
     */
    'ww.merx.gateways' => [
        'prepayment' => [],
    ],

    'hooks' => [
        'page.create:after' => function ($page) {
            /**
             * Product variant pages have a generated title.
             * We want to use the title entered in creation dialog for variantName
             * and remove the title field.
             * See /site/models/product-variant.php
             */
            if ($page->intendedTemplate()->name() === 'product-variant') {
                $page->update([
                    'variantName' => $page->content()->title(),
                    'title' => null,
                ]);
            }
        },
        'page.update:after' => function ($newPage, $oldPage) {
            if ($newPage->intendedTemplate()->name() === 'order' && $newPage->isListed()) {
                /**
                 * For the “prepayment” payment method the paidDate is not set automatically after
                 * the user completes the checkout (as in contrast to e.g. PayPal or credit card payment).
                 * This hook sets the paid date when paymentComplete field switches form false to true.
                 */
                if ($newPage->paymentComplete()->toBool() !== $oldPage->paymentComplete()->toBool()) {
                    if ($newPage->paymentComplete()->isTrue()) {
                        kirby()->impersonate('kirby');
                        $newPage = $newPage->update([
                            'paidDate' => date('c'),
                        ]);
                    } else {
                        $newPage->update([
                            'paidDate' => '',
                        ]);
                    }
                }
            }
        },
        'route:after' => function () {
            /**
             * Sets strict Content Security Policy (CSP) and other security related headers for non-panel URLs
             */
            if (!Str::startsWith($this->request()->url()->toString(), $this->urls()->panel)) {
                header('X-Frame-Options: deny');
                header('X-Content-Type-Options: nosniff');
                header('Content-Security-Policy: default-src \'none\'; script-src \'self\' https://js.stripe.com; connect-src \'self\'; img-src \'self\'; style-src \'self\'; base-uri \'self\'; form-action \'self\'; child-src https://js.stripe.com');
            }
        },
        'ww.merx.cart' => function ($cart) {
            /**
             * Update shipping
             * https://merx.wagnerwagner.de/cookbooks/shipping-costs-and-discounts
             */
            $site = site();
            if ($site->shippingPage()) {
                $shippingId = $site->shippingPage()->id();
                $freeShipping = $site->shippingPage()->freeShipping()->or('0')->toFloat();
                $cart->remove($shippingId);
                if ($cart->count() > 0 && $cart->getSum() < $freeShipping) {
                    $cart->add($shippingId);
                }
            }
        },
        'ww.merx.completePayment:after' => function (OrderPage $orderPage) {
            /**
             * Update stock
             * https://merx.wagnerwagner.de/cookbooks/stock-management
             */
            if (option('debug') !== true) {
                foreach($orderPage->cart() as $cartItem) {
                    $productPage = page($cartItem['id']);
                    if ($productPage && $productPage->stock()->isNotEmpty()) {
                        $stock = $productPage->stock()->toFloat();
                        $productPage->update([
                            'stock' => $stock - (float)$cartItem['quantity'],
                        ]);
                    }
                }
            }
        },
    ],
];
