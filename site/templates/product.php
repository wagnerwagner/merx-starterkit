<?php /** @var ProductPage $page */ ?>
<?php snippet('head') ?>

<body>
  <?php snippet('header') ?>

  <main class="grid product" data-id="<?= $page->id() ?>">
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
      <div class="price">
        <?= $page->price()->toformattedPrice() ?>
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
    </div>
  </main>

  <?php snippet('footer') ?>
</body>

<?php snippet('foot') ?>
