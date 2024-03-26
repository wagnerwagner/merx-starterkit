<h2 data-width="1/1"><?= t('coupon') ?></h2>
<div class="coupon" data-width="1/1" style="display: flex; align-items: end; gap: var(--spacing-m);">
  <div style="flex-grow: 1;" class="field" data-name="couponcode" data-type="text">
    <label for="couponcode"><?= t('coupon.code') ?></label>
    <input id="couponcode" name="couponcode" type="text" value="start5">
    <button hidden type="button" name="remove-coupon" class="button-secondary" formaction="/api/shop/coupon" formnovalidate><?= t('coupon.remove') ?></button>
  </div>
  <button type="button" name="add-coupon" class="button-secondary" formaction="/api/shop/coupon" formnovalidate><?= t('coupon.redeem') ?></button>
</div>

