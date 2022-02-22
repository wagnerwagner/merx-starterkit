<?php /** @var ProductVariantsPage $page */ ?>
<?php snippet('head') ?>

<body>
  <?php snippet('header') ?>

  <main class="grid product product-variants" data-id="<?= $page->defaultVariant()->id() ?>" data-parent-id="<?= $page->id() ?>">
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
      <div class="stack-s">
        <label class="color-gray-700" for="color"><?= t('product.color') ?></label>
        <select data-action="update-variant" id="color">
          <?php foreach ($page->variants() as $variant) : ?>
            <option
              value="<?= $variant->id() ?>"
              <?= $page->defaultVariant() === $variant ? 'selected' : '' ?>
              data-uid="<?= $variant->uid() ?>"
              data-image="<?= $variant->thumb()->toFile()->thumb('default')->url() ?>"
              data-image-width="<?= $variant->thumb()->toFile()->thumb('default')->width() ?>"
              data-image-height="<?= $variant->thumb()->toFile()->thumb('default')->height() ?>"
              data-image-alt="<?= $variant->thumb()->toFile()->alt() ?>"
              data-price="<?= $variant->price()->toFormattedPrice() ?>"
              data-stock-info="<?= $variant->stockInfo() ?>"
            >
              <?= $variant->variantName() ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="price">
        <?= $page->defaultVariant()->price()->toFormattedPrice() ?>
      </div>
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
  </main>

  <?php snippet('footer') ?>
</body>

<?php snippet('foot') ?>
