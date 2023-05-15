<?php
$stripePublishableKey = option('ww.merx.production') === true ? option('ww.merx.stripe.live.publishable_key') : option('ww.merx.stripe.test.publishable_key');
$paypalPublishableKey = option('ww.merx.production') === true ? option('ww.merx.paypal.live.clientID') : option('ww.merx.paypal.sandbox.clientID');
?>
<div class="payment-methods grid" data-width="1/1">
  <h2 data-width="1/1"><?= t('field.paymentMethod') ?></h2>

  <?php foreach(collection('payment-methods') as $paymentMethod): ?>
    <?php
      $fieldDisabled = false;
      if (
        ($paymentMethod['paymentProvider'] === 'stripe' && empty($stripePublishableKey))
        || ($paymentMethod['paymentProvider'] === 'paypal' && empty($paypalPublishableKey))
      ) {
        $fieldDisabled = true;
      }
    ?>
      <div class="field" data-type="radio" data-width="4/12">
        <input
          <?= Html::attr([
            'type' => 'radio',
            'name' => 'paymentMethod',
            'id' => $paymentMethod['value'],
            'value' => $paymentMethod['value'],
            'disabled' => $fieldDisabled,
            'required' => true,
            'data-submit-text' => $paymentMethod['submitText'] ?? null,
          ]) ?>
        >
        <label for="<?= $paymentMethod['value'] ?>">
          <?= $paymentMethod['label'] ?>
        </label>
      </div>
  <?php endforeach; ?>

  <?php if (!empty($stripePublishableKey) && collection('payment-methods')->has('credit-card')): ?>
    <div class="field" data-width="1/1" data-payment-method="credit-card-sca" hidden>
      <label for="">
        <?= t('paymentMethod.creditCard.label') ?>
      </label>
      <div id="stripe-card"></div>
    </div>
  <?php endif; ?>
  <?php if (!empty($stripePublishableKey) && collection('payment-methods')->has('sepa-debit')): ?>
    <div class="field" data-width="1/1" data-payment-method="sepa-debit" hidden>
      <label for="">
        <?= t('paymentMethod.sepaDebit.label') ?>
      </label>
      <div id="stripe-iban"></div>
      <small>By providing your IBAN and confirming this payment, you are authorizing <?= $site->title() ?> and Stripe, our payment service provider, to send instructions to your bank to debit your account and your bank to debit your account in accordance with those instructions. You are entitled to a refund from your bank under the terms and conditions of your agreement with your bank. A refund must be claimed within 8 weeks starting from the date on which your account was debited.</small>
    </div>
  <?php endif; ?>
  <?php if (!empty($stripePublishableKey) && (collection('payment-methods')->has('credit-card') || collection('payment-methods')->has('sepa-debit') || collection('payment-methods')->has('sofort'))): ?>
  <input type="hidden" name="stripePublishableKey" value="<?= $stripePublishableKey ?>">
  <script src="https://js.stripe.com/v3/"></script>
  <?php endif; ?>
</div>
