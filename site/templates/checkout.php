<?php
/**
 * Shows a form to collect user data and payment information.
 * The form is build using the personalData section of the order blueprint.
 * See site/controllers/checkout.php for more information.
 * Each field type has a own snippet (e.g. snippet('fields/text') - /site/snippets/fields/text.php).
 * If no suitable snippet is found, fields/text snippet is used.
 *
 * @var array $fields
 * @var mixed $message
 */
?>
<?php snippet('head') ?>

<body>
  <?php snippet('header') ?>
  <?php if ($message): ?>
    <div class="global-message"><?= $message ?></div>
  <?php endif; ?>

  <main class="checkout">
    <h1><?= $page->title() ?></h1>

    <div class="cart" id="cart" data-variant="checkout" data-theme="dark"></div>

    <form class="grid" method="post" id="checkout-form" action="/api/shop/checkout">
      <?php foreach ($fields as $field) : ?>
        <?= snippet(['fields/' . $field['type'], 'fields/text'], compact('field')) ?>
      <?php endforeach; ?>
      <?php snippet('payment-methods') ?>
      <div data-width="1/1">
        <button type="submit" class="button">Buy</button>
      </div>
    </form>
    <?php if (option('debug') === true && option('ww.merx.production')  === false): ?>
      <?php
      $paypalPublishableKey = option('ww.merx.production') === true ? option('ww.merx.paypal.live.clientID') : option('ww.merx.paypal.sandbox.clientID');
      $stripePublishableKey = option('ww.merx.production') === true ? option('ww.merx.stripe.live.publishable_key') : option('ww.merx.stripe.test.publishable_key');
      ?>
      <div class="notice text">
        <h2>Testing Payment Methods</h2>
        <?php if (empty($paypalPublishableKey) && empty($stripePublishableKey)): ?>
          <p>You can use <em>Prepayment</em> as a payment method to complete your order. If you wish to use payment methods that depend on third party payment providers (Stripe or PayPal), please follow the instructions below.</p>
        <?php else: ?>
          <p>You can use the following credentials to test the checkout.</p>
        <?php endif ?>
        <h3>PayPal</h3>
        <?php if (empty($paypalPublishableKey)): ?>
          <p>
            To use/test PayPal payments, you will need to login your <a href="https://www.paypal.com/signin?returnUri=https%3A%2F%2Fdeveloper.paypal.com%2Fdeveloper%2Fapplications" rel="noopener">PayPal Developer Dashboard</a> and create a client ID and a secret. You have to add these keys to <code>site/config/config.php</code> (see <a href="https://merx.wagnerwagner.de/docs/getting-started/set-up#options" rel="noopener">Merx options</a>).
          </p>
        <?php else: ?>
          <p>
            <strong>User</strong> <code>sb-47n60q14173052@personal.example.com</code><br>
            <strong>Password</strong> <code>XTDeYw-P7Lzpc-L1u7xd</code><br>
          </p>
        <?php endif ?>

        <?php if (empty($stripePublishableKey)): ?>
          <h3>Credit Card, SEPA Direct Debit and Klarna (Stripe)</h3>
          <p>To use/test one of these payment methods, you will need to login to your Stripe account and create a publishable and a private key. You have to add these keys to <code>site/config/config.php</code> (see <a href="https://merx.wagnerwagner.de/docs/getting-started/set-up#options" rel="noopener">Merx options</a>).</p>
        <?php else: ?>
          <h3>Credit Card</h3>
          <p>
            <strong>Visa</strong> <code>4242424242424242</code><br>
            <strong>3D Secure</strong> <code>4000002500003155</code><br>
          </p>
          <p>
            <strong>CVC</strong> Any 3 digits<br>
            <strong>Date</strong> Any future date
          </p>
          <p><small><a href="https://stripe.com/docs/testing#cards" rel="noopener">stripe.com/docs/testing#cards</a></small></p>
          <h3>SEPA Direct Debit</h3>
          <p><code>DE89370400440532013000</code></p>
          <p><small><a href="https://stripe.com/docs/testing#sepa-direct-debit" rel="noopener">stripe.com/docs/testing#sepa-direct-debit</a></small></p>
        <?php endif ?>

      </div>
    <?php endif; ?>
  </main>

  <?php snippet('footer') ?>
</body>

<?php snippet('foot') ?>
