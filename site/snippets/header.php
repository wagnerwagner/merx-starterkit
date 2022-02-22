<?php /** @var Kirby\Cms\Page $page */ ?>
<?php /** @var Kirby\Cms\Site $site */ ?>
<?php /** @var Kirby\Cms\App $kirby */ ?>
<header class="header">
  <a href="<?= $site->url() ?>" class="logo">
    <?= $site->title() ?>
  </a>
  <div class="lang-switch">
    <?php foreach ($kirby->languages()->not($kirby->language()) as $language) : ?>
      <a href="<?= $page->url($language->code()) ?>"><?= $language->name() ?></a>
    <?php endforeach; ?>
  </div>
  <?php if ($page->template()->name() === 'checkout'): ?>
    <a class="link-cart" href="<?= $site->checkoutPage()->url() ?>">
      <?= t('cart') ?> <span class="cart-count"></span>
    </a>
  <?php else: ?>
    <details class="details-cart">
      <summary data-action="toggle-cart">
        <?= t('cart') ?> <span class="cart-count"></span>
      </summary>
      <div class="cart" id="cart" data-theme="dark">
        <?php
        /**
         * The cart is loaded asynchronously. This is why you can make use of Kirby’s page cache and
         * provide your users a very fast web shop.
         * The cart’s content is loaded by `assets/js/cart.js`.
         */
        ?>
      </div>
    </details>
  <?php endif; ?>
  <nav class="header-nav">
    <?php foreach ($site->children()->listed() as $item) : ?>
      <a href="<?= $item->url() ?>"><?= $item->title() ?></a>
    <?php endforeach ?>
  </nav>
</header>
