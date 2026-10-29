<?php
/**
 * Headline to group fields
 *
 * @author Tobias Wolf
 * @see https://getkirby.com/docs/reference/panel/fields/headline
 * @var array $field
 *  Example:
 *  array(8) {
 *    ["default"] => NULL
 *    ["disabled"] => bool(true)
 *    ["hidden"] => bool(false)
 *    ["label"] => string(8) "Shipping"
 *    ["name"] => string(16) "headlineshipping"
 *    ["saveable"] => bool(false)
 *    ["type"] => string(8) "headline"
 *    ["width"] => string(3) "1/1"
 *  }
 */
?>
<h2
  class="field"
  data-width="<?= $field['width'] ?>"
>
  <?= $field['label'] ?>
</h2>
