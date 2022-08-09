<?php
/**
 * This is the supplementary, custom plugin for the shop.
 * It provides custom API routes (e.g. POST /api/shop/cart/),
 * custom global functions and site methods.
 */

/**
  * Checks if requested amount of a specific item could be added to cart
  *
  * @param ProductPage|ProductVariantPage $productPage
  */
function checkStock(object $productPage, float $quantity)
{
    $siteMaxAmount = $productPage->site()->maxAmount()->toFloat();
    $maxAmount = $productPage->maxAmount();
    $productTitle = $productPage->title();
    if ($quantity > $siteMaxAmount) {
        $maxAmount = $siteMaxAmount;
        $exceptionMessage = tt('stock.maxAmount', compact('maxAmount', 'productTitle'));
    } elseif ($quantity > $maxAmount) {
        if ($productPage->maxAmount() === 0) {
            $exceptionMessage = tt('stock.not-available', compact('productTitle'));
        } elseif ($productPage->maxAmount() === 1) {
            $exceptionMessage = tt('stock.not-available.1', compact('productTitle'));
        } else {
            $availableStock = $maxAmount;
            $exceptionMessage = tt('stock.not-available.count', compact('productTitle', 'availableStock'));
        }
    }
    if (isset($exceptionMessage)) {
        throw new Exception($exceptionMessage);
    }
}

Kirby::plugin('site/site', [
    'api' => [
        'data' => [
            'cart' => require_once(__DIR__ . '/api/data/cart.php'),
        ],
        'routes' => [
            require_once(__DIR__ . '/api/routes/cart.php'),
            require_once(__DIR__ . '/api/routes/cart-post.php'),
            require_once(__DIR__ . '/api/routes/cart-patch.php'),
            require_once(__DIR__ . '/api/routes/client-secret.php'),
            require_once(__DIR__ . '/api/routes/checkout-post.php'),
        ],
    ],
    'fieldMethods' => [
        'toIntlDate' => function (
            $field,
            int $dateType = IntlDateFormatter::MEDIUM,
            int $timeType = IntlDateFormatter::SHORT,
            /** @var IntlTimeZone|DateTimeZone|string|null $timezone */
            $timezone = null,
            /** @var IntlCalendar|int|null $calendar */
            $calendar = null,
            string $pattern = ""
        ): ?string
        {
            if (empty($field->toString())) {
                // return string when field is empty
                return $field->toString();
            }
            $locale = Kirby\Toolkit\Locale::get(LC_TIME);
            $date = new Kirby\Toolkit\Date($field->toString());
            if ($timezone === null) {
                $timezone = $date->getTimezone();
            }
            // https://www.php.net/manual/en/class.intldateformatter.php
            $format = new IntlDateFormatter(
                $locale,
                $dateType,
                $timeType,
                $timezone,
                $calendar,
                $pattern,
            );
            return $format->format($date);
        }
    ],
    'siteMethods' => [
        'shippingPage' => function (): ?\Kirby\Cms\Page {
            return $this->children()->template('shipping')->first();
        },
        'checkoutPage' => function (): ?\Kirby\Cms\Page {
            return $this->children()->template('checkout')->first();
        },
    ],
]);
