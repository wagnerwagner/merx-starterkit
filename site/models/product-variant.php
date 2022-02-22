<?php

/**
 * ProductVariant pages are children
 * of a ProductVariants page.
 * Many fields are the same as in ProductPage.
 *
 * @author Tobias Wolf
 */

class ProductVariantPage extends Kirby\Cms\Page
{
    /**
     * Overwrites title method to get a auto generated
     * title with ProductVariants’ title and its own
     * variant name (e.g. “Coffee Cup, green“)
     *
     * @return Field
     */
    public function title(): Field
    {
        $value = $this->parent()->title() . ', ' . $this->variantName();
        return new Field($this, 'title', $value);
    }

    public function url($options = null): string
    {
        return $this->parent()->url($options) . '#' . $this->uid();
    }

    public function maxAmount(): float
    {
        if ($this->stock()->isNotEmpty() && $this->stock()->toFloat() < $this->site()->maxAmount()->toFloat()) {
            return $this->stock()->toFloat();
        }
        return $this->site()->maxAmount()->toFloat();
    }

    public function tax(): float
    {
        if ($this->content()->tax()->isEmpty()) {
            return 0;
        }
        return $this->kirby()->option('taxRates')[$this->content()->tax()->toString()];
    }


    public function stockInfo(): ?string
    {
        $stockInfo = null;
        $stock = $this->stock()->exists() ? $this->stock()->toInt() : null;
        $productTitle = $this->title();
        if ($stock === 0) {
            $stockInfo = tt('product.stock.sold-out', compact('productTitle'));
        } else if ($stock === 1) {
            $stockInfo = tt('product.stock.info.1', compact('productTitle'));
        } else if ($stock <= 5) {
            $stockInfo = tt('product.stock.info', compact('productTitle', 'stock'));
        }
        return $stockInfo;
    }
}
