<?php

use Kirby\Cms\App;
use Kirby\Cms\Event;
use Kirby\Toolkit\Str;
use Wagnerwagner\Merx\Cart;
use Wagnerwagner\Merx\Logger;

return [
	'kql' => [
		'auth' => false,
	],

	'languages' => true,
	'thumbs' => [
		'quality' => 80,
		'presets' => [
			'default' => ['width' => 704],
			'thumb' => ['width' => 46, 'height' => 46, 'crop' => true],
		],
		'srcsets' => [
			'default' => [
				'352w' => ['width' => 352],
				'704w' => ['width' => 704],
				'1408w' => ['width' => 1408],
			],
			'thumb' => [
				'46w' => ['width' => 46, 'height' => 46, 'crop' => true],
				'92w' => ['width' => 92, 'height' => 92, 'crop' => true],
			],
		],
	],

	/**
	 * This array is used to generate the tax field (in percent) for every product.
	 * Used in
	 * /site/blueprints/fields/product-standards.yml
	 * /site/models/product.php
	 * /site/models/product-variant.php
	 */
	'wagnerwagner.merx.taxRules' => [
		'default' => [
			'name' => fn (): string => t('taxRule.default'),
			'rule' => fn (?App $kirby): float => $kirby->languageCode() === 'de' ? 0.19 : 0.20,
		],
		'reduced' => [
			'name' => fn (): string => t('taxRule.reduced'),
			'rule' => function (?App $kirby): ?float {
				switch ($kirby->languageCode()) {
					case 'de':
						return 0.07;
					case 'us':
						return 0.055;
					default:
						return null;
				}
			},
		],
	],

	/**
	 * Key value pair for pricing key and the matching currency.
	 * Used to return prices with correct currency.
	 */
	'wagnerwagner.merx.pricingRules' => [
		'default' => [
			'name' => 'EUR',
			'currency' => 'EUR',
			'rule' => fn (?App $kirby): bool => $kirby->languageCode() !== 'us',
			'taxIncluded' => true,
		],
		'en' => [
			'name' => 'USD',
			'currency' => 'USD',
			'rule' => fn (?App $kirby): bool => $kirby->languageCode() === 'us',
			'taxIncluded' => false,
		],
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
	'wagnerwagner.merx.stripe.test.publishable_key' => '',
	'wagnerwagner.merx.stripe.test.secret_key' => '',
	'wagnerwagner.merx.paypal.sandbox.clientID' => '',
	'wagnerwagner.merx.paypal.sandbox.secret' => '',

	'wagnerwagner.merx.stripe.live.publishable_key' => '',
	'wagnerwagner.merx.stripe.live.secret_key' => '',
	'wagnerwagner.merx.paypal.live.clientID' => '',
	'wagnerwagner.merx.paypal.live.secret' => '',

	'wagnerwagner.merx.production' => false,
	// 'wagnerwagner.merx.license' => 'MERX-XXXXXXXX-XXXXXXXX',

	'wagnerwagner.merx.stripe.paymentIntentParameters' => [
		'payment_method_types' => ['card', 'ideal', 'sepa_debit', 'klarna'],
		'automatic_payment_methods' => ['enabled' => false],
		'capture_method' => 'automatic_async',
		'payment_method_options' => [
			'card' => ['capture_method' => 'manual'],
		],
	],

	'hooks' => [
		'system.exception' => function (Throwable $exception) {
			try {
				if (option('debug') !== true) {
					if (method_exists($exception, 'toArray')) {
						Logger::log($exception->toArray(), 'error');
					} else {
						// Ensure we get basic exception details even if toArray isn't available
						Logger::log([
							'message' => $exception->getMessage(),
							'code' => $exception->getCode(),
							'file' => $exception->getFile(),
							'line' => $exception->getLine()
						], 'error');
					}
				}
			} catch (Throwable) {}
		},
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
		'page.update:after' => function (\Kirby\Cms\Page $newPage, \Kirby\Cms\Page $oldPage) {
			if ($newPage->intendedTemplate()->name() === 'order' && $newPage->isListed()) {
				/**
				 * For the “prepayment” payment method the datePaid is not set automatically after
				 * the user completes the checkout (as in contrast to e.g. PayPal or credit card payment).
				 * This hook sets the paid date when paymentComplete field switches form false to true.
				 */
				if ($newPage->paymentComplete()->toBool() !== $oldPage->paymentComplete()->toBool()) {
					if ($newPage->paymentComplete()->isTrue()) {
						$newPage->update([
							'datePaid' => date('c'),
						]);
					} else {
						$newPage->update([
							'datePaid' => '',
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
				// header('X-Frame-Options: deny');
				// header('X-Content-Type-Options: nosniff');
				// header('Content-Security-Policy: default-src \'none\'; script-src \'self\' https://js.stripe.com; connect-src \'self\'; img-src \'self\'; style-src \'self\'; base-uri \'self\'; form-action \'self\'; child-src https://js.stripe.com');
			}
		},
		'wagnerwagner.merx.cart.*:after' => function (Event $event, Cart $cart, $key = null) {
			/**
			 * Update shipping
			 * https://merx.wagnerwagner.de/cookbooks/shipping-costs-and-discounts
			 */

			$site = site();
			if ($shippingPage = $site->shippingPage()) {
				$shippingId = (string)$shippingPage->uuid();

				if ($event->name() === 'wagnerwagner.merx.cart.remove:after' && $key === $shippingId) {
					// Avoid loop
					return;
				}

				$freeShipping = $shippingPage->freeShipping()->or('0')->toFloat();
				$cart = $cart->remove($shippingId);
				if ($cart->count() > 0 && $cart->total()?->toFloat() < $freeShipping) {
					$cart->add([
						'key' => $shippingId,
						'type' => 'shipping',
					]);
				}
			}
		},
		'wagnerwagner.merx.completePayment:after' => function (OrderPage $orderPage) {
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
