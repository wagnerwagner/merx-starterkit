<?php
/**
 * SECURITY ADVICE
 * This page contains (sensitive) user data, such as name, address, email, etc.
 * By default this page is only protected by its cryptic URL (e.g. /orders/e8vsmomjbb8vjtp5).
 * These URLs can’t be guessed but if an attacker knows the URL it has access to user data.
 * To prevent this, you might add an extra layer of security.
 * More information: https://merx.wagnerwagner.de/docs/security
 *
 * Merx stores what the customer entered as it is. Every field which comes
 * from the checkout form has to be escaped here, with `$field->escape()`.
 *
 * @var OrderPage $page
 * @var string $paymentMethod
 * @var string $total
 */

 use Wagnerwagner\Merx\Merx;

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
				<strong><?= t('order.date') ?> <?= $page->dateCreated()->toIntlDate() ?></strong>
			</p>
			<p class="text" data-width="2/3">
				<?= $page->name()->escape() ?><br>
				<?php if ($page->organization()->isNotEmpty()): ?>
					<?= $page->organization()->escape() ?><br>
				<?php endif; ?>
				<?= $page->streetAddress()->escape() ?><br>
				<?= $page->postalCode()->escape() ?> <?= $page->city()->escape() ?><br>
				<?= $page->country()->escape() ?><br>
			</p>
			<div data-width="1/1">
				<?php snippet('cart', ['cart' => $page->cart()]); ?>
			</div>
			<div class="text" data-width="1/2">
				<h2><?= t('order.payment') ?></h2>
				<p><?= tt('order.payment.text', compact('paymentMethod')) ?></p>
				<?php if ($page->isPaid() === true): ?>
					<p><?= tt('order.payment.text.paid.date', ['datetime' => $page->datePaid()->toIntlDate()]) ?></p>
				<?php endif; ?>
				<?php if ($page->paymentMethod()->toString() === 'prepayment' && $page->isPaid() === false): ?>
					<p>
						<?= t('order.payment.text.not-yet-paid') ?><br>
						<?= tt('order.payment.text.invoice', [
							'amount' => $total,
						]) ?>
					</p>
					<table class="table">
						<tr>
							<th><?= t('order.invoice.recipient') ?></th>
							<td><?= $site->title() ?></td>
						</tr>
						<tr>
							<th><?= t('order.invoice.iban') ?></th>
							<td><?= Merx::formatIBAN('DE0000000000000000') ?></td>
						</tr>
						<tr>
							<th><?= t('order.invoice.total') ?></th>
							<td><?= $total ?></td>
						</tr>
						<tr>
							<th><?= t('order.invoice.purpose') ?></th>
							<td><?= $page->title() ?></td>
						</tr>
					</table>
				<?php endif; ?>
			</div>
			<div class="text" data-width="1/2">
				<h2><?= t('order.shipping-address') ?></h2>
				<p>
					<?php if ($page->billingAddressIsShippingAddress()->toBool() === true): ?>
						<?= $page->name()->escape() ?><br>
						<?php if ($page->organization()->isNotEmpty()): ?>
							<?= $page->organization()->escape() ?><br>
						<?php endif; ?>
						<?= $page->streetAddress()->escape() ?><br>
						<?= $page->postalCode()->escape() ?> <?= $page->city()->escape() ?><br>
						<?= $page->country()->escape() ?>
					<?php else: ?>
						<?= $page->shippingName()->escape() ?><br>
						<?php if ($page->shippingOrganization()->isNotEmpty()): ?>
							<?= $page->shippingOrganization()->escape() ?><br>
						<?php endif; ?>
						<?= $page->shippingStreetAddress()->escape() ?><br>
						<?= $page->shippingPostalCode()->escape() ?> <?= $page->shippingCity()->escape() ?><br>
						<?= $page->shippingCountry()->escape() ?>
					<?php endif; ?>
				</p>
			</div>
		</div>
	</main>

	<?php snippet('footer') ?>
</body>

<?php snippet('foot') ?>
