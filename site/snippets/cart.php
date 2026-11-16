<?php
/**
 * The cart of an order, see /site/templates/order.php
 * The same table as /assets/js/my-cart.js renders for the cart of the visitor.
 *
 * @var Wagnerwagner\Merx\ProductList $cart
 */
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
				<?php /** @var Wagnerwagner\Merx\ListItem $item */ ?>
				<tr>
					<th>
						<?php if ($itemPage = $item->page): ?>
							<a href="<?= $itemPage->url() ?>">
						<?php endif ?>
							<?php /** The product may be gone by now. Its title is kept in the order. */ ?>
							<?php if ($thumb = $itemPage?->thumb()->toFile()): ?>
								<img src="<?= $thumb->thumb('thumb')->url() ?>" srcset="<?= $thumb->srcset('thumb') ?>" sizes="46px" alt="<?= $thumb->alt()->escape() ?>">
							<?php endif ?>
							<?= esc($item->title) ?>
						<?php if ($itemPage): ?>
							</a>
						<?php endif ?>
					</th>
					<td><?= $item->quantity ?></td>
					<td><?= $item->price ?></td>
					<td><?= $item->total() ?></td>
				</tr>
			<?php endforeach ?>
		</tbody>
		<tfoot>
			<?php $shipping = $cart->filterByType('shipping')->total() ?>
			<tr>
				<th colspan="3"><?= t('cart.shipping') ?></th>
				<td><?= $shipping?->price > 0 ? $shipping : t('cart.free-shipping') ?></td>
			</tr>
			<tr>
				<th colspan="3"><?= t('cart.total') ?></th>
				<?php /** The amount to pay: the gross total, also when the prices above are net prices, as in the USA */ ?>
				<td><?= $cart->total()->toString('price') ?></td>
			</tr>
			<?php /** Keyed by their rate, e.g. `0.19`. Numbered from 0 for the first row. */ ?>
			<?php $taxRates = array_values($cart->taxRates()) ?>
			<?php foreach ($taxRates as $index => $tax): ?>
				<tr class="text-s color-gray-500">
					<?php if ($index === 0): ?>
						<th colspan="3" rowspan="<?= count($taxRates) ?>"><?= t('cart.included-vat') ?></th>
					<?php endif ?>
					<td class="vat"><span><?= $tax->rate() ?></span> <?= $tax ?></td>
				</tr>
			<?php endforeach ?>
		</tfoot>
	</table>
</div>
