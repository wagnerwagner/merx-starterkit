<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
?>
<!doctype html>
<html lang="<?= $kirby->language()->code() ?>">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<?php /** Make sure order pages are not indexed by search engines. */ ?>
	<?php if (option('debug') === true || $page->intendedTemplate()->name() === 'order'): ?>
		<meta name="robots" content="noindex,nofollow">
	<?php endif; ?>
	<title><?= $page->title() ?> – <?= $site->title() ?></title>
	<?= css('assets/css/index.css') ?>
	<?= js('assets/js/my-cart.js', ['defer' => true]) ?>
	<?php if (in_array($page->intendedTemplate()->name(), ['product', 'product-variants'])) : ?>
		<?= css('assets/css/templates/product.css') ?>
		<?= js('assets/js/templates/product.js', ['defer' => true]) ?>
	<?php endif; ?>
	<?php if ($page->intendedTemplate()->name() === 'product-variants') : ?>
		<?= js('assets/js/templates/product-variants.js', ['defer' => true]) ?>
	<?php endif; ?>
	<?php if ($page->intendedTemplate()->name() === 'checkout') : ?>
		<?= css('assets/css/templates/checkout.css') ?>
		<?= js('assets/js/templates/checkout.js', ['defer' => true]) ?>
		<?= js('assets/js/templates/conditional-fields.js', ['defer' => true,]) ?>
	<?php endif; ?>
	<?php if ($page->intendedTemplate()->name() === 'order') : ?>
		<?= css('assets/css/templates/order.css') ?>
	<?php endif; ?>
</head>
