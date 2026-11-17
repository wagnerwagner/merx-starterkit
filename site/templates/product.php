<?php
/** @var ProductPage $page */

// dump(option('wagnerwagner.merx.taxRules'));
// die();
?>
<?php snippet('head') ?>

<body>
	<?php snippet('header') ?>

	<main class="grid product">
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
					<?php snippet('price', ['product' => $page]) ?>
				</div>

				<div class="stack-s">
					<form class="product-form" action="<?= url('api/shop/cart') ?>" method="post">
						<input type="hidden" name="page" value="<?= $page->uuid() ?>">
						<button class="button">
							<?= t('product.add-to-cart') ?>
						</button>
					</form>

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
