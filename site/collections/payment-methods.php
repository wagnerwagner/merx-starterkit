<?php
return function (): Kirby\Toolkit\Collection
{
    return new Kirby\Toolkit\Collection([
        'paypal' => [
            'value' => 'paypal',
            'label' => t('paymentMethod.paypal.label'),
            'submitText' => t('paymentMethod.paypal.submitText'),
						'paymentGateway' => 'paypal'
        ],
        'credit-card' => [
            'value' => 'card',
            'label' => t('paymentMethod.creditCard.label'),
						'paymentGateway' => 'stripe-elements'
        ],
        'prepayment' => [
            'value' => 'prepayment',
            'label' => t('paymentMethod.prepayment.label'),
						'paymentGateway' => 'invoice'
        ],
        'sepa-debit' => [
            'value' => 'sepa-debit',
            'label' => t('paymentMethod.sepaDebit.label'),
						'paymentGateway' => 'stripe-elements'
        ],
        'klarna' => [
            'value' => 'klarna',
            'label' => t('paymentMethod.klarna.label'),
            'submitText' => t('paymentMethod.klarna.submitText'),
						'paymentGateway' => 'stripe-elements'
        ],
        'ideal' => [
            'value' => 'ideal',
            'label' => t('paymentMethod.ideal.label'),
            'submitText' => t('paymentMethod.ideal.submitText'),
						'paymentGateway' => 'stripe-elements'
        ],
    ]);
};
