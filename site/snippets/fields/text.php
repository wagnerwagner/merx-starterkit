<?php

use Kirby\Cms\Html;
use Kirby\Toolkit\I18n;

/**
 * Snippet for standard, single-line input field.
 * Fallback for fields with no own snippet.
 *
 * @author Tobias Wolf
 * @see https://getkirby.com/docs/reference/panel/fields/text
 * @see https://github.com/getkirby/kirby/blob/main/panel/src/components/Forms/Field/TextField.vue
 * @var array $field
 *  `disabled` is always true, because customers cannot edit pages in the
 *  Panel. It is ignored here: a browser does not submit a disabled input.
 *  Example:
 *  array(16) {
 *    ["autofocus"] => bool(false)
 *    ["counter"] => bool(true)
 *    ["default"] => string(0) ""
 *    ["disabled"] => bool(true)
 *    ["font"] => string(10) "sans-serif"
 *    ["hidden"] => bool(false)
 *    ["label"] => string(4) "Name"
 *    ["maxlength"] => int(160)
 *    ["minlength"] => int(3)
 *    ["name"] => string(12) "shippingname"
 *    ["required"] => bool(true)
 *    ["saveable"] => bool(true)
 *    ["translate"] => bool(false)
 *    ["type"] => string(4) "text"
 *    ["when"] => array(1) {
 *      ["billingAddressIsShippingAddress"] => bool(false)
 *    }
 *    ["width"] => string(3) "1/1"
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
      'max' => $field['max'] ?? null,
      'maxlength' => $field['maxlength'] ?? null,
      'min' => $field['min'] ?? null,
      'minlength' => $field['minlength'] ?? null,
    ]) ?>
  >
  <?php if (isset($field['help'])): ?>
    <div class="color-gray-600 text-s">
      <?= $field['help'] ?>
    </div>
  <?php endif; ?>
</div>
