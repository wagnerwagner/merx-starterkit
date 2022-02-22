<?php
return function (): Kirby\Toolkit\Collection
{
    return new Kirby\Toolkit\Collection([
        'paypal' => [
            'value' => 'paypal',
            'label' => t('paymentMethod.paypal.label'),
            'submitText' => t('paymentMethod.paypal.submitText'),
        ],
        'credit-card' => [
            'value' => 'credit-card-sca',
            'label' => t('paymentMethod.creditCard.label'),
        ],
        'prepayment' => [
            'value' => 'prepayment',
            'label' => t('paymentMethod.prepayment.label'),
        ],
        'sepa-debit' => [
            'value' => 'sepa-debit',
            'label' => t('paymentMethod.sepaDebit.label'),
        ],
        'sofort' => [
            'value' => 'sofort',
            'label' => t('paymentMethod.sofort.label'),
            'submitText' => t('paymentMethod.sofort.submitText'),
        ],
    ]);
};
