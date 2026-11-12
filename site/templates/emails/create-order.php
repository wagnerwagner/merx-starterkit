<?php /** @var \Wagnerwagner\Merx\OrderPage $orderPage */ ?>
<?= tt('email.createOrder.greeting', ['name' => $orderPage->name()]) ?>

<?= tt('email.createOrder.text', [
	'orderNumber' => $orderPage->orderNumber(),
	'total' => $orderPage->total()->toString('price'),
]) ?>

<?= $orderPage->url() ?>

<?= $orderPage->site()->title() ?>
