<?php
/**
 * Shows a form to collect user data and payment information.
 * The form is build using the personalData section of the order blueprint.
 * See site/controllers/checkout.php for more information.
 * Each field type has a own snippet (e.g. snippet('fields/text') - /site/snippets/fields/text.php).
 * If no suitable snippet is found, fields/text snippet is used.
 *
 * @var array $fields
 * @var mixed $message
 */
?>
<?php snippet('head') ?>

<body>
	<?php snippet('header') ?>

	<main class="checkout">
		<h1><?= $page->title() ?></h1>

		<div class="cart" id="cart" data-variant="checkout" data-theme="dark"></div>

		<form class="grid" method="post" id="checkout-form" action="/api/shop/checkout">
			<?php foreach ($fields as $field) : ?>
				<?= snippet(['fields/' . $field['type'], 'fields/text'], compact('field')) ?>
			<?php endforeach; ?>
			<?php snippet('payment-methods') ?>
			<div data-width="1/1">
				<button type="submit" class="button">Buy</button>
			</div>
		</form>
		<?php if (option('debug') === true && option('wagnerwagner.merx.production')	=== false): ?>
			<?php snippet('checkout-info') ?>
			</div>
		<?php endif; ?>
	</main>

	<?php snippet('footer') ?>
</body>

<?php snippet('foot') ?>
