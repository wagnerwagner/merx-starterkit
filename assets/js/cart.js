// This script fills the `div.cart` element (e.g. /site/snippets/header.php).
// It also handles adding items, update items and deleting items from the cart.
// The instance of Cart is stored as a global variabel (`window.cart`).

class Cart {
  constructor(element) {
    this.lang = document.querySelector('html').lang;

    this.element = element;
    this.cartDetailsElement = document.querySelector('.details-cart');
    this.countElement = document.querySelector('.cart-count');

    // this object will be used to store the cart data we are loading from the api.
    this.data = {};

    // store language variables
    // this should be the same as in /site/languages/en.php
    this.i18n = {
      'cart.empty': 'Cart is empty.',
      'cart.item.remove': 'Remove',
      'cart.included-vat': 'Included VAT',
      'cart.vat-included': 'VAT incl.',
      'cart.quantity': 'Quantity',
      'cart.quantity-in-cart': 'in cart',
      'cart.change-quantity': 'Change quantity',
      'cart.price': 'Price',
      'cart.sum': 'Sum',
      'cart.shipping': 'Shipping',
      'cart.product': 'Product',
      'cart.free-shipping': 'free',
      'cart.to-checkout': 'To Checkout',
    };
    // overwrite default language variables
    if (this.lang === 'de') {
      this.i18n = {
        'cart.empty': 'Der Warenkorb ist leer.',
        'cart.item.remove': 'Entfernen',
        'cart.included-vat': 'Enthaltene MwSt.',
        'cart.vat-included': 'inkl. MwSt.',
        'cart.quantity': 'Anzahl',
        'cart.quantity-in-cart': 'im Warenkorb',
        'cart.change-quantity': 'Anzahl ändern',
        'cart.price': 'Preis',
        'cart.sum': 'Summe',
        'cart.shipping': 'Versand',
        'cart.product': 'Produkt',
        'cart.free-shipping': 'kostenlos',
        'cart.to-checkout': 'Zur Kasse',
      };
    }

    this.cartDetailsElement?.addEventListener('click', (event) => {
      event.stopPropagation();
    });

    document.addEventListener('click', () => {
      this.cartDetailsElement?.removeAttribute('open');
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        this.cartDetailsElement?.removeAttribute('open');
      }
    });

    // initially load cart data from api
    this.request('cart');
  }

  // helper method to handle different api request
  // the api endpoint is defined in /site/plugins/site/api.php
  request(endpoint, method = 'GET', data = null) {
    const { lang } = this;

    return fetch(`/api/shop/${endpoint}`, {
      method,
      headers: {
        'Content-Type': 'application/json',
        'x-language': lang,
      },
      body: method !== 'GET' ? JSON.stringify(data) : null,
    })
      .then((response) => response.json())
      .then((json) => {
        if (json.status === 'ok') {
          // store response data to data object.
          this.data = json.data;
          this.updateHTML();
          this.updateCount();
        } else {
          alert(json.message);
        }
        return json;
      });
  }

  updateCount() {
    const { data } = this;
    if (this.countElement) {
      if (data.quantity === 0) {
        this.countElement.innerText = '';
      } else {
        this.countElement.innerText = `(${data.quantity})`;
      }
    }
  }

  updateHTML() {
    const { data, i18n } = this;
    function createQuantitySelect(item) {
      const ariaLabel = `${item.quantity} ${i18n['cart.quantity-in-cart']}. ${i18n['cart.change-quantity']}.`;
      let html = `<select data-key="${item.key}" aria-label="${ariaLabel}">`;
      for (let i = 0; i <= item.maxAmount; i += 1) {
        html += `
          <option ${i === item.quantity ? 'selected' : ''}>
            ${i}
          </option>
        `;
      }
      html += '</select>';
      return html;
    }

    function createCartItem(item) {
      return `
        <tr>
          <th>
            <a href="${item.url}">
              <img src="${item.thumb?.url}" alt="${item.thumb?.alt}" width="46" height="46">
              <strong>${item.title}</strong>
              ${item.variant ? `<small>${item.variant}</small>` : ''}
            </a>
          </th>
          <td>${createQuantitySelect(item)}</td>
          <td>${item.price}</td>
          <td>${item.sum}</td>
        </tr>
      `;
    }

    function createCartItems(items) {
      let html = '';
      items.forEach((item) => {
        html += createCartItem(item);
      });
      return html;
    }

    function createTaxRates(taxRates) {
      let html = '';
      taxRates.forEach((taxRate) => {
        html += `
          <tr class="text-s color-gray-500">
            <th colspan="3">${i18n['cart.included-vat']} (${taxRate.taxRate} %)</th>
            <td>${taxRate.sum}</td>
          </tr>
        `;
      });
      return html;
    }

    if (data.quantity === 0) {
      this.element.innerHTML = `
        <div class="cart-info">${i18n['cart.empty']}</div>
      `;
    } else {
      this.element.innerHTML = `
        <table>
          <thead class="color-gray-500">
            <tr>
              <th>${i18n['cart.product']}</th>
              <td>${i18n['cart.quantity']}</td>
              <td>${i18n['cart.price']}</td>
              <td>${i18n['cart.sum']}</td>
            </tr>
          </thead>
          <tbody>
            ${createCartItems(data.items)}
          </tbody>
          <tfoot>
            <tr>
              <th colspan="3">${i18n['cart.shipping']}</th>
              <td>${data.shipping === null ? i18n['cart.free-shipping'] : data.shipping}</td>
            </tr>
            <tr>
              <th colspan="3">${i18n['cart.sum']}</th>
              <td>${data.sum}</td>
            </tr>
            ${createTaxRates(data.taxRates)}
          </tfoot>
        </table>
        ${(this.element.dataset.variant !== 'checkout') ? `<a href="${data.checkoutUrl}" class="button-white">${i18n['cart.to-checkout']}</a>` : ''}
      `;

      this.element.querySelectorAll('select').forEach((selectElement) => {
        selectElement.addEventListener('change', (event) => {
          const { key } = event.target.dataset;
          const quantity = event.target.value;
          this.update(key, quantity);
        });
      });
    }
  }

  add(id, quantity = 1) {
    this.element.classList.add('-loading');
    return this.request('cart', 'POST', {
      id,
      quantity,
    }).finally(() => {
      this.cartDetailsElement?.setAttribute('open', '');
      this.element.classList.remove('-loading');
    });
  }

  update(id, quantity = 1) {
    this.element.classList.add('-loading');
    return this.request('cart', 'PATCH', {
      id,
      quantity,
    }).finally(() => {
      this.element.classList.remove('-loading');
    });
  }
}

const cartElement = document.getElementById('cart');
if (cartElement) {
  // Store the instance of Cart as a global variable so other scripts can make use of Cart methods.
  window.cart = new Cart(cartElement);
}
