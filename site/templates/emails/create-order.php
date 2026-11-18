<?php
/**
 * The hook runs for every order, also for those which are not paid yet,
 * e.g. prepayment or SEPA Direct Debit.
 * https://merx.wagnerwagner.de/guide/getting-started/sending-emails
 *
 * @var \Wagnerwagner\Merx\OrderPage $orderPage
 */
?>
<?= tt('email.createOrder.greeting', ['name' => $orderPage->name()]) ?>

<?= tt($orderPage->isPaid() ? 'email.createOrder.text' : 'email.createOrder.text.not-yet-paid', [
	'orderNumber' => $orderPage->orderNumber(),
	'total' => $orderPage->total()->toString('price'),
]) ?>

<?= $orderPage->url() ?>

<?= $orderPage->site()->title() ?>
