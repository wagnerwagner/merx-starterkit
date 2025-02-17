<?php

/**
 * It is required to create an Order Page Model
 * https://merx.wagnerwagner.de/docs/getting-started/set-up#order-page-model
 *
 * @author Tobias Wolf
 */

class OrderPage extends \Wagnerwagner\Merx\OrderPage
{
    public function title(): Field
    {
        return new Field($this, 'title', t('order.invoice') . ' ' . $this->invoiceNumber());
    }
}
