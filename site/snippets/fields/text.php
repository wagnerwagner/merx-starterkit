<?php
/**
 * Snippet for standard, single-line input field.
 * Fallback for fields with no own snippet.
 *
 * @author Tobias Wolf
 * @see https://getkirby.com/docs/reference/panel/fields/text
 * @see https://github.com/getkirby/kirby/blob/main/panel/src/components/Forms/Field/TextField.vue
 * @var array $field
 *  Example:
 *  array(15) {
 *    ["autofocus"]  => bool(false)
 *    ["counter"] => bool(true)
 *    ["default"]=> string(16) "mail@example.com"
 *    ["disabled"] => bool(false)
 *    ["label"] => string(4) "Name"
 *    ["name"] => string(12) "shippingname"
 *    ["required"] => bool(true)
 *    ["saveable"] => bool(true)
 *    ["signature"]  => string(32) "267fa7b7de75f1834bd505210dfb5227"
 *    ["spellcheck"] => bool(false)
 *    ["strict"] => bool(true)
 *    ["translate"] => bool(false)
 *    ["type"] => string(4) "text"
 *    ["validate"] => array(1) {
 *      ["minLength"] => int(3)
 *      ["maxLength"] => int(160)
 *    }
 *    ["when"] => array(1) {
 *      ["billingAddressIsShippingAddress"] => bool(false)
 *    }
 *    ["width"]      => string(3) "1/1"
 *  }
 */
?>
<div
  class="field"
  data-width="<?= $field['width'] ?>"
  data-name="<?= $field['name'] ?>"
  <?php if (array_key_exists('when', $field)) : ?>
    data-when='<?= json_encode($field['when']) ?>'
  <?php endif; ?>
  data-type="<?= $field['type'] ?>"
>
  <label for="<?= $field['name'] ?>">
    <?= $field['label'] ?>
    <?php if ($field['required']): ?>
      <abbr title="<?= I18n::translate('field.required') ?>">*</abbr>
    <?php endif; ?>
  </label>
  <input
    <?= Html::attr([
      'type' => $field['type'],
      'name' => $field['name'],
      'id' => $field['name'],
      'required' => $field['required'] ?? null,
      'placeholder' => isset($field['placeholder']) ? $field['placeholder'] : null,
      'value' => isset($field['default']) ? $field['default'] : null,
      'autocomplete' => isset($field['autocomplete']) ? $field['autocomplete'] : null,
      'max' => isset($field['validate']['max']) ?$field['validate']['max'] : null,
      'maxLength' => isset($field['validate']['maxLength']) ?$field['validate']['maxLength'] : null,
      'min' => isset($field['validate']['min']) ?$field['validate']['min'] : null,
      'minLength' => isset($field['validate']['minLength']) ?$field['validate']['minLength'] : null,
    ]) ?>
  >
  <?php if (isset($field['help'])): ?>
    <div class="color-gray-600 text-s">
      <?= $field['help'] ?>
    </div>
  <?php endif; ?>
</div>
