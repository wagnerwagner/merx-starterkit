<?php
/**
 * This is the supplementary, custom plugin for the shop.
 * It provides custom field methods and site methods.
 */

use Kirby\Cms\App;

App::plugin('site/site', [
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
    ],
]);
