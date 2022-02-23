<?php
/**
 * SECURITY ADVICE
 * This page contains (sensitive) user data, such as name, address, email, etc.
 * By default this page is only protected by its cryptic URL (e.g. /orders/e8vsmomjbb8vjtp5).
 * These URLs can’t be guessed but if an attacker knows the URL it has access to user data.
 * To prevent this, you might add an extra layer of security.
 * More information: https://merx.wagnerwagner.de/docs/security
 *
 * @var OrderPage $page
 * @var string $paymentMethod
 * @var string $sum
 */
?>
<?php snippet('head') ?>

<body>
  <?php snippet('header') ?>

  <main class="order">
    <div class="grid">
      <h1 data-width="1/1">
        <?= $page->title() ?>
      </h1>
      <p data-width="1/1">
        <strong><?= t('order.date') ?> <?= $page->invoiceDate()->toIntlDate() ?></strong>
      </p>
      <p class="text" data-width="2/3">
        <?= $page->name() ?><br>
        <?php if ($page->organization()->isNotEmpty()): ?>
          <?= $page->organization() ?><br>
        <?php endif; ?>
        <?= $page->streetAddress() ?><br>
        <?= $page->postalCode() ?> <?= $page->city() ?><br>
        <?= $page->country() ?><br>
      </p>
      <div data-width="1/1">
        <?php snippet('cart', ['cart' => $page->cart()]); ?>
      </div>
      <div class="text" data-width="1/2">
        <h2><?= t('order.payment') ?></h2>
        <p><?= tt('order.payment.text', compact('paymentMethod')) ?></p>
        <?php if ($page->paymentMethod()->toString() === 'prepayment'): ?>
          <?php if ($page->paymentComplete()->toBool() === true): ?>
            <p><?= tt('order.payment.text.paid.date', ['datetime' => $page->paidDate()->toIntlDate()]) ?></p>
          <?php else: ?>
            <p>
              <?= t('order.payment.text.not-yet-paid') ?><br>
              <?= tt('order.payment.text.invoice', compact('sum')) ?>
            </p>
            <table class="table">
              <tr>
                <th><?= t('order.invoice.recipient') ?></th>
                <td><?= $site->title() ?></td>
              </tr>
              <tr>
                <th><?= t('order.invoice.iban') ?></th>
                <td><?= formatIBAN('DE0000000000000000') ?></td>
              </tr>
              <tr>
                <th><?= t('order.invoice.sum') ?></th>
                <td><?= $sum ?></td>
              </tr>
              <tr>
                <th><?= t('order.invoice.purpose') ?></th>
                <td><?= $page->title() ?></td>
              </tr>
            </table>
            <p></p>
          <?php endif; ?>
        <?php endif; ?>
      </div>
      <div class="text" data-width="1/2">
        <h2><?= t('order.shipping-address') ?></h2>
        <p>
          <?php if ($page->billingAddressIsShippingAddress()->toBool() === true): ?>
            <?= $page->name() ?><br>
            <?php if ($page->organization()->isNotEmpty()): ?>
              <?= $page->organization() ?><br>
            <?php endif; ?>
            <?= $page->streetAddress() ?><br>
            <?= $page->postalCode() ?> <?= $page->city() ?><br>
            <?= $page->country() ?>
          <?php else: ?>
            <?= $page->shippingName() ?><br>
            <?php if ($page->shippingOrganization()->isNotEmpty()): ?>
              <?= $page->shippingOrganization() ?><br>
            <?php endif; ?>
            <?= $page->shippingStreetAddress() ?><br>
            <?= $page->shippingPostalCode() ?> <?= $page->shippingCity() ?><br>
            <?= $page->shippingCountry() ?>
          <?php endif; ?>
        </p>
      </div>
    </div>
  </main>

  <?php snippet('footer') ?>
</body>

<?php snippet('foot') ?>
