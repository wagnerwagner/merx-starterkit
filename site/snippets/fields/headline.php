<?php
/**
 * Headline to group fields
 *
 * @author Tobias Wolf
 * @see https://getkirby.com/docs/reference/panel/fields/headline
 * @var array $field
 *  Example:
 *  array(8) {
 *    ["label"] => string(8) "Shipping"
 *    ["name"] => string(16) "headlineshipping"
 *    ["numbered"] => bool(false)
 *    ["saveable"] => bool(false)
 *    ["signature"] => string(32) "249e3c48b86e40cf8fe4818d6488c02c"
 *    ["strict"] => bool(true)
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
