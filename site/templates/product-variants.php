<?php /** @var ProductVariantsPage $page */ ?>
<?php snippet('head') ?>

<body>
	<?php snippet('header') ?>

	<main class="grid product product-variants">
		<h1 data-width="1/1"><?= $page->title() ?></h1>
		<img
			src="<?= $page->thumb()->toFile()->thumb('default')->url() ?>"
			alt="<?= $page->thumb()->toFile()->alt() ?>"
			width="<?= $page->thumb()->toFile()->thumb('default')->width() ?>"
			height="<?= $page->thumb()->toFile()->thumb('default')->height() ?>"
			data-width="2/3"
		>
		<form class="product-form" action="<?= url('api/shop/cart') ?>" method="post" data-width="1/3">
			<div class="stack-m">
				<div class="blocks">
					<?= $page->blocks()->toBlocks() ?>
				</div>
				<div class="stack-s">
					<label class="color-gray-700" for="color"><?= t('product.color') ?></label>
					<?php /** Sends the selected variant as `page`, see https://merx.wagnerwagner.de/cookbook/product-variants */ ?>
					<select data-action="update-variant" id="color" name="page">
						<?php foreach ($page->variants() as $variant) : ?>
							<option
								value="<?= $variant->uuid() ?>"
								<?= $page->defaultVariant() === $variant ? 'selected' : '' ?>
								data-uid="<?= $variant->uid() ?>"
								data-image="<?= $variant->thumb()->toFile()->thumb('default')->url() ?>"
								data-image-width="<?= $variant->thumb()->toFile()->thumb('default')->width() ?>"
								data-image-height="<?= $variant->thumb()->toFile()->thumb('default')->height() ?>"
								data-image-alt="<?= $variant->thumb()->toFile()->alt() ?>"
								data-stock-info="<?= $variant->stockInfo() ?>"
							>
								<?= $variant->variantName() ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="price">
					<?php snippet('price', ['product' => $page->defaultVariant()]) ?>
				</div>
				<?php foreach ($page->variants() as $variant) : ?>
					<template data-price="<?= $variant->uuid() ?>">
						<?php snippet('price', ['product' => $variant]) ?>
					</template>
				<?php endforeach ?>
				<div class="stack-s">
					<button
						class="button"
						data-action="add-to-cart"
					>
						<?= t('product.add-to-cart') ?>
					</button>
					<div class="color-gray-600" data-slot="stockInfo">
						<?= $page->defaultVariant()->stockInfo() ?>
					</div>
				</div>
			</div>
		</form>
	</main>

	<?php snippet('footer') ?>
</body>

<?php snippet('foot') ?>
