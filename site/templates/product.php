<?php
/** @var ProductPage $page */

// dump(option('wagnerwagner.merx.taxRules'));
// die();
?>
<?php snippet('head') ?>

<body>
	<?php snippet('header') ?>

	<main class="grid product" data-id="<?= $page->uuid() ?>" data-currency="<?= $page->price()?->currency() ?>">
		<h1 data-width="1/1"><?= $page->title() ?></h1>
		<img
			src="<?= $page->thumb()->toFile()->thumb('default')->url() ?>"
			alt="<?= $page->thumb()->toFile()->alt() ?>"
			width="<?= $page->thumb()->toFile()->thumb('default')->width() ?>"
			height="<?= $page->thumb()->toFile()->thumb('default')->height() ?>"
			data-width="2/3"
		>
		<div class="stack-m" data-width="1/3">
			<div class="blocks">
				<?= $page->blocks()->toBlocks() ?>
			</div>
			<?php if ($page->price()): ?>
				<div class="price">
					<?= $page->price()?->toString() ?>
					<?php if ($page->tax() === null): ?>
						<small><?= t('product.tax-free') ?></small>
					<?php else: ?>
						<small>
							<?php if ($page->price()->taxIncluded()): ?>
								<?= tt('product.including-tax', ['tax' => $page->tax()?->rate()]) ?> (<?= $page->tax()?->toString() ?>)<br>
								<?= tt('product.net-price', ['price' => $page->price()->toString('priceNet')]) ?>
							<?php else: ?>
								<?= tt('product.excluding-tax', ['tax' => $page->tax()?->rate()]) ?> (<?= $page->tax()?->toString() ?>)<br>
								<?= tt('product.gross-price', ['price' => $page->price()->toString('price')]) ?>
							<?php endif ?>
						</small>
					<?php endif ?>
				</div>

				<div class="stack-s">
					<button
						class="button"
						data-action="add-to-cart"
					>
						<?= t('product.add-to-cart') ?>
					</button>

					<?php if ($page->stockInfo()): ?>
						<div class="color-gray-600">
							<?= $page->stockInfo() ?>
						</div>
					<?php endif; ?>
				</div>
			<?php else: ?>
				<div class="color-gray-600">
					<?= tt('product.unavailable', ['region' => $page->kirby()->language()->name()]) ?>
				</div>
			<?php endif ?>
		</div>
	</main>

	<?php snippet('footer') ?>
</body>

<?php snippet('foot') ?>
