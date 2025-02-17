<?php

use Wagnerwagner\Merx\ListItem;
use Wagnerwagner\Merx\Merx;
use Wagnerwagner\Merx\Price;

/**
 * Used for the order page template.
 * PHP equivalent to /assets/js/cart.js
 *
 * @var Wagnerwagner\Merx\Cart $cart
 */

$shippingPrice = new Price(0);
foreach ($cart->filterByType('shipping') as $shippingItem) {
  $shippingPrice->price += $shippingItem->total()->price;
  $shippingPrice->currency = $shippingItem->price->currency;
}
?>
<div class="cart">
  <table>
    <thead>
      <tr>
        <th><?= t('cart.product') ?></th>
        <td><?= t('cart.quantity') ?></td>
        <td><?= t('cart.price') ?></td>
        <td><?= t('cart.total') ?></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($cart->filterByType('product') as $item): ?>
        <?php /** @var ListItem $item */ ?>
        <tr>
          <th>
            <?php /** Get more information about the product by catching the product page */ ?>
            <?php if ($productPage = $item->page): ?>
              <a href="<?= $productPage->url() ?>">
                <?php if ($thumb = $productPage->thumb()->toFile()): ?>
                  <img src="<?= $thumb->thumb('thumb')->url() ?>" alt="<?= $thumb->alt() ?>" width="46" height="46" loading="lazy">
                <?php endif; ?>
                <?php if ($productPage->intendedTemplate()->name() === 'product-variant'): ?>
                  <strong><?= $productPage->parent()->title() ?></strong>
                  <small><?= $productPage->variantName() ?></small>
                <?php else: ?>
                  <strong><?= $item->title ?></strong>
                <?php endif; ?>
              </a>
            <?php else: ?>
            <?php /** If the a product page won’t be available anymore, at least the product title will be shown */ ?>
              <strong><?= $item->title ?? $item->key ?></strong>
            <?php endif; ?>
          </th>
          <td><?= $item->quantity ?></td>
          <td><?= $item->price->toString() ?></td>
          <td><?= $item->total()->toString() ?></td>
        </tr>
      <?php endforeach ?>
    </tbody>
    <tfoot>
      <tr>
        <th colspan="3"><?= t('cart.shipping') ?></th>
        <td><?= $shippingPrice->price === 0.0 ? t('cart.free-shipping') : $shippingPrice->toString() ?></td>
      </tr>
      <tr>
        <th colspan="3"><?= t('cart.total') ?></th>
        <td><?= $cart->total()->toString() ?></td>
      </tr>
      <?php foreach ($cart->taxRates() as $taxRate): ?>
        <tr class="text-s">
          <th colspan="3"><?= t('cart.included-vat') ?> (<?= Merx::formatPercent($taxRate->rate) ?>)</th>
          <td><?= $taxRate->price->toString() ?></td>
        </tr>
      <?php endforeach; ?>
    </tfoot>
  </table>
</div>
