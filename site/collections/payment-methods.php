<?php
return function (): Kirby\Toolkit\Collection
{
    return new Kirby\Toolkit\Collection([
        'paypal' => [
            'value' => 'paypal',
            'label' => t('paymentMethod.paypal.label'),
            'submitText' => t('paymentMethod.paypal.submitText'),
            'paymentProvider' => 'paypal',
        ],
        'credit-card' => [
            'value' => 'credit-card-sca',
            'label' => t('paymentMethod.creditCardSca.label'),
            'paymentProvider' => 'stripe',
        ],
        'prepayment' => [
            'value' => 'prepayment',
            'label' => t('paymentMethod.prepayment.label'),
            'paymentProvider' => null,
        ],
        'sepa-debit' => [
            'value' => 'sepa-debit',
            'label' => t('paymentMethod.sepaDebit.label'),
            'paymentProvider' => 'stripe',
        ],
        'klarna' => [
            'value' => 'klarna',
            'label' => t('paymentMethod.klarna.label'),
            'submitText' => t('paymentMethod.klarna.submitText'),
            'paymentProvider' => 'stripe',
        ],
        'ideal' => [
            'value' => 'ideal',
            'label' => t('paymentMethod.ideal.label'),
            'submitText' => t('paymentMethod.ideal.submitText'),
            'paymentProvider' => 'stripe',
        ],
    ]);
};
