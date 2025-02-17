<?php /** @var \Kirby\Cms\Pages<\Wagnerwagner\Merx\ProductPage> $products */ ?>
<?php snippet('head') ?>

<body>
  <?php snippet('header') ?>

  <main>
    <?php
    /** @var \Wagnerwagner\Merx\Cart $cart */
    $cart = cart();
    cart()->add([
      'key' => 'test',
      'priceNet' => 14,
      'tax' => 0.1,
    ]);
    dump($kirby->collection('prices')->toArray());
    ?>
    <div class="product-grid">
      <?php foreach($products as $product): ?>
        <a href="<?= $product->url() ?>" class="product-grid-item">
          <figure>
            <img src="<?= $product->thumb()->toFile()->crop('520')->url() ?>" width="520" height="520" alt="<?= $product->alt() ?>">
            <figcaption>
              <strong><?= $product->title() ?></strong>
              <em><?= $product->price()?->toString() ?></em>
            </figcaption>
          </figure>
        </a>
      <?php endforeach; ?>
    </div>
  </main>

  <?php snippet('footer') ?>
</body>

<?php snippet('foot') ?>
