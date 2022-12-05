<?php
/**
 * Yes/no toggle aka checkbox
 *
 * @author Tobias Wolf
 * @see https://getkirby.com/docs/reference/panel/fields/toggle
 * @var array $field
 *  Example:
 *  array(13) {
 *    ["autofocus"] => bool(false)
 *    ["default"] => bool(true)
 *    ["disabled"] => bool(false)
 *    ["label"] => string(39) "Use billing address as shipping address"
 *    ["name"] => string(31) "billingaddressisshippingaddress"
 *    ["required"] => bool(false)
 *    ["saveable"] => bool(true)
 *    ["signature"] => string(32) "99e373c372d83d369b81d8cb90a6dacb"
 *    ["strict"] => bool(true)
 *    ["text"] => array(2) {
 *      [0] => string(2) "No"
 *      [1] => string(3) "Yes"
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
      'checked' => $field['default'],
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
