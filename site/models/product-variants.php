<?php

use Kirby\Cms\Page;

/**
 * Page model of products with several
 * product variants (e.g. Coffee Cup with
 * different colors). This class provides
 * some helper methods.
 *
 * @author Tobias Wolf
 */

class ProductVariantsPage extends Page
{
    /**
     * Returns the thumb field of the default variant
     *
     * @return \Kirby\Content\Field|null
     */
    public function thumb()
    {
        if ($this->defaultVariant()) {
            return $this->defaultVariant()->thumb();
        }
        return null;
    }

    /**
     * Returns all product variants
     *
     * @return \Kirby\Cms\Pages
     */
    public function variants(): \Kirby\Cms\Pages
    {
        return $this->children()->listed()->filter('intendedTemplate', 'product-variant');
    }

    /**
     * Returns default variant defined by
     * defaultVariant content field.
     *
     * @return ProductVariantPage|null
     */
    public function defaultVariant(): ?ProductVariantPage
    {
        return $this->content()->defaultVariant()->toPage();
    }

    /**
     * @return \Kirby\Content\Field|null
     */
    public function price(): ?Field
    {
        if ($this->defaultVariant()) {
            return $this->defaultVariant()->price();
        }
        return null;
    }
}
