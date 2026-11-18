<?php

use Kirby\Content\Field;

/**
 * It is required to create an Order Page Model
 * https://merx.wagnerwagner.de/guide/upgrade-merx-1-to-2#order-page-model
 *
 * @author Tobias Wolf
 */

class OrderPage extends \Wagnerwagner\Merx\OrderPage
{
	/**
	 * @return \Kirby\Content\Field|null
	 */
	public function title(): Field
	{
		// A draft has no order number yet. Merx titles it “Incomplete”.
		if ($this->isDraft()) {
			return parent::title();
		}

		return new Field($this, 'title', t('order.invoice') . ' ' . $this->orderNumber());
	}
}
