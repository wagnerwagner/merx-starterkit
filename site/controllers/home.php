<?php

return function (Kirby\Cms\Pages $pages) {
    $products = $pages->filterBy('intendedTemplate', 'in', ['product', 'product-variants']);

    return [
        'products' => $products,
    ];
};
