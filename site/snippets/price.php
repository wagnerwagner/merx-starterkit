<?php
/**
 * The price of a product with its tax
 *
 * @var \Wagnerwagner\Merx\ProductPage $product
 */
$price = $product->price();
$tax = $product->tax();
?>
<?php if ($price): ?>
	<?= $price->toString() ?>
	<?php if ($tax === null): ?>
		<small><?= t('product.tax-free') ?></small>
	<?php else: ?>
		<small>
			<?php if ($price->taxIncluded()): ?>
				<?= tt('product.including-tax', ['tax' => $tax->rate()]) ?> (<?= $tax->toString() ?>)<br>
				<?= tt('product.net-price', ['price' => $price->toString('priceNet')]) ?>
			<?php else: ?>
				<?= tt('product.excluding-tax', ['tax' => $tax->rate()]) ?> (<?= $tax->toString() ?>)<br>
				<?= tt('product.gross-price', ['price' => $price->toString('price')]) ?>
			<?php endif ?>
		</small>
	<?php endif ?>
<?php endif ?>
