<?php
/**
 * The cart of the visitor. It is empty here and rendered by
 * /assets/js/my-cart.js, which gets its labels from `data-translations`.
 *
 * @var bool $popover The cart in the header opens as a popover and links to the checkout
 */
?>
<my-cart <?= attr([
	'id' => 'cart',
	'popover' => $popover ?? false,
	'data-translations' => json_encode([
		'empty' => t('cart.empty'),
		'product' => t('cart.product'),
		'quantity' => t('cart.quantity'),
		'price' => t('cart.price'),
		'total' => t('cart.total'),
		'remove' => t('cart.item.remove'),
		'shipping' => t('cart.shipping'),
		'free' => t('cart.free-shipping'),
		'vat' => t('cart.included-vat'),
	]),
]) ?>></my-cart>
