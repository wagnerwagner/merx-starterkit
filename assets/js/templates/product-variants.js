// Corresponding Class for site/templates/product-variant.php

class ProductVariants {
  constructor(element) {
    const selectElement = element.querySelector('select[data-action="update-variant"]');
    const priceElement = element.querySelector('.price');
    const imageElement = element.querySelector('img');
    const slotStockInfoElement = element.querySelector('[data-slot="stockInfo"]');

    function updateProductVariant() {
      const selectedOption = selectElement.selectedOptions[0];

      if (selectedOption) {
        // update element’s “id”. This id is used to add the product to the cart (see product.js).
        element.setAttribute('data-id', selectElement.value);

        // update price and image
        priceElement.innerText = selectedOption.dataset.price;
        imageElement.setAttribute('src', selectedOption.dataset.image);
        imageElement.setAttribute('alt', selectedOption.dataset.imageAlt);
        imageElement.setAttribute('width', selectedOption.dataset.imageWidth);
        imageElement.setAttribute('height', selectedOption.dataset.imageHeight);

        // update stock info
        slotStockInfoElement.innerText = selectedOption.dataset.stockInfo;

        // update url the get a unique uri for each product variant
        window.location.hash = `#${selectedOption.dataset.uid}`;
      }
    }

    function useHashToUpdateProductVariant() {
      // Use “substr(1)” to remove first “#”
      const hash = window.location.hash.substr(1);

      // update select element if url hash a hash value
      if (hash) {
        selectElement.value = `${element.dataset.parentId}/${hash}`;
        updateProductVariant();
      }
    }

    selectElement.addEventListener('change', updateProductVariant);
    window.addEventListener('hashchange', useHashToUpdateProductVariant);

    useHashToUpdateProductVariant();
  }
}

document.querySelectorAll('.product-variants').forEach((productElement) => new ProductVariants(productElement));
