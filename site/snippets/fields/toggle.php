<?php

use Kirby\Cms\Html;
use Kirby\Toolkit\I18n;

/**
 * Yes/no toggle aka checkbox
 *
 * @author Tobias Wolf
 * @see https://getkirby.com/docs/reference/panel/fields/toggle
 * @var array $field
 *  Example:
 *  array(12) {
 *    ["autofocus"] => bool(false)
 *    ["default"] => string(4) "true"
 *    ["disabled"] => bool(true)
 *    ["hidden"] => bool(false)
 *    ["label"] => string(39) "Use billing address as shipping address"
 *    ["name"] => string(31) "billingaddressisshippingaddress"
 *    ["required"] => bool(false)
 *    ["saveable"] => bool(true)
 *    ["text"] => array(2) {
 *      [0] => object(Kirby\Toolkit\HtmlString) "No"
 *      [1] => object(Kirby\Toolkit\HtmlString) "Yes"
 *    }
 *    ["translate"] => bool(false)
 *    ["type"] => string(6) "toggle"
 *    ["width"] => string(3) "1/1"
 *  }
 */
?>
<div
  class="field"
  data-width="<?= $field['width'] ?>"
  data-name="<?= $field['name'] ?>"
  <?php foreach ($field['when'] ?? [] as $key => $value) : ?>
    data-when-<?= $key ?>="<?= $value ?>"
  <?php endforeach; ?>
  data-type="checkbox"
>
  <input
    <?= Html::attr([
      'type' => 'hidden',
      'name' => $field['name'],
      'value' => 'false',
    ]) ?>
  >
  <input
    <?= Html::attr([
      'type' => 'checkbox',
      'name' => $field['name'],
      'id' => $field['name'],
      'checked' => $field['default'] ?? null,
      'value' => 'true',
    ]) ?>
  >
  <label for="<?= $field['name'] ?>">
    <?= $field['label'] ?>
    <?php if ($field['required']): ?>
      <abbr title="<?= I18n::translate('field.required') ?>">*</abbr>
    <?php endif; ?>
  </label>
  <?php if (isset($field['help'])): ?>
    <div class="color-gray-600 text-s">
      <?= $field['help'] ?>
    </div>
  <?php endif; ?>
</div>
