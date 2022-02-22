<?php /** @var Kirby\Cms\Pages $products */ ?>
<?php snippet('head') ?>

<body>
  <?php snippet('header') ?>

  <main>
    <div class="product-grid">
      <?php foreach($products as $product): ?>
        <a href="<?= $product->url() ?>" class="product-grid-item">
          <figure>
            <img src="<?= $product->thumb()->toFile()->crop('520')->url() ?>" width="520" height="520" alt="<?= $product->alt() ?>">
            <figcaption>
              <strong><?= $product->title() ?></strong>
              <em><?= formatPrice($product->price()->toFloat()) ?></em>
            </figcaption>
          </figure>
        </a>
      <?php endforeach; ?>
    </div>
  </main>

  <?php snippet('footer') ?>
</body>

<?php snippet('foot') ?>
