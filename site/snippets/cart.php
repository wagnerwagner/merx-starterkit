<?php
/**
 * Used for the order page template.
 * PHP equivalent to /assets/js/cart.js
 *
 * @var Wagnerwagner\Merx\Cart $cart
 */

$shippingPrice = 0;
foreach ($cart->filter('template', 'shipping') as $shippingItem) {
    $shippingPrice += $shippingItem['price'];
}
?>
<div class="cart">
  <table>
    <thead>
      <tr>
        <th><?= t('cart.product') ?></th>
        <td><?= t('cart.quantity') ?></td>
        <td><?= t('cart.price') ?></td>
        <td><?= t('cart.sum') ?></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($cart->filter('template', '!=', 'shipping') as $item): ?>
        <tr>
          <th>
            <?php /** Get more information about the product by catching the product page */ ?>
            <?php if ($productPage = page($item['id'])): ?>
              <a href="<?= $productPage->url() ?>">
                <?php if ($thumb = $productPage->thumb()->toFile()): ?>
                  <img src="<?= $thumb->thumb('thumb')->url() ?>" alt="<?= $thumb->alt() ?>" width="46" height="46" loading="lazy">
                <?php endif; ?>
                <?php if ($productPage->intendedTemplate()->name() === 'product-variant'): ?>
                  <strong><?= $productPage->parent()->title() ?></strong>
                  <small><?= $productPage->variantName() ?></small>
                <?php else: ?>
                  <strong><?= $item['title'] ?></strong>
                <?php endif; ?>
              </a>
            <?php else: ?>
            <?php /** If the a product page won’t be available anymore, at least the product title will be shown */ ?>
              <strong><?= $item['title'] ?></strong>
            <?php endif; ?>
          </th>
          <td><?= $item['quantity'] ?></td>
          <td><?= formatPrice($item['price']) ?></td>
          <td><?= formatPrice($item['sum']) ?></td>
        </tr>
      <?php endforeach ?>
    </tbody>
    <tfoot>
      <tr>
        <th colspan="3"><?= t('cart.shipping') ?></th>
        <td><?= $shippingPrice === 0 ? t('cart.free-shipping') : formatPrice($shippingPrice) ?></td>
      </tr>
      <tr>
        <th colspan="3"><?= t('cart.sum') ?></th>
        <td><?= formatPrice($cart->getSum()) ?></td>
      </tr>
      <?php foreach ($cart->getTaxRates() as $taxRate): ?>
        <tr class="text-s">
          <th colspan="3"><?= t('cart.included-vat') ?> (<?= $taxRate['taxRate'] ?> %)</th>
          <td><?= formatPrice($taxRate['sum']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tfoot>
  </table>
</div>
