<?php

use Kirby\Cms\Page;
use Kirby\Form\Fields;

/**
 * Provides the template with the fields of the “personalData” tab of the
 * order blueprint (/site/blueprints/pages/order.yml), which shows the fields
 * of /site/blueprints/fields/personal-data.yml.
 *
 * The order page only exists for this request and is never saved. Kirby needs
 * a page to build the fields for.
 *
 * More information:
 * https://merx.wagnerwagner.de/cookbook/build-checkout-form-from-blueprint
 */

return function () {
	$order = Page::factory(['slug' => 'checkout', 'template' => 'order']);
	$names = $order->blueprint()->tab('personalData')->fieldNames();

	return [
		'fields' => Fields::for($order)
			->filter(fn ($field) => in_array($field->name(), $names, true))
			->toProps(defaults: true),
	];
};
