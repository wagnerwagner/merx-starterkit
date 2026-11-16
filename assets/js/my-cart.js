/**
 * The cart of the visitor: <my-cart>, see /site/snippets/my-cart.php
 *
 * It is loaded from Merx’ API after the page, so every page can be cached.
 * https://merx.wagnerwagner.de/guide/getting-started/showing-the-cart
 */

/** A price as the shop shows it: net, when the pricing rule excludes tax (USA) */
const amount = (price) => (price?.taxIncluded === false ? price.priceNet : price?.price) ?? '';

customElements.define('my-cart', class extends HTMLElement {
	/** The translated labels, see /site/snippets/my-cart.php */
	label = {};

	/** @see https://merx.wagnerwagner.de/reference/api/models/cart */
	cart = null;

	async connectedCallback() {
		this.label = JSON.parse(this.dataset.translations);
		this.render(await this.request('GET'));
	}

	/** Adds the product of a form, see /assets/js/templates/product.js */
	async addItem(/** @type HTMLFormElement */ form) {
		this.render(await this.request('POST', Object.fromEntries(new FormData(form))));
		this.showPopover();
	}

	/** Sends a request to the cart API of Merx and returns the cart */
	async request(method, body) {
		const response = await fetch('/api/shop/cart', {
			method,
			headers: { 'X-Language': document.documentElement.lang },
			body: body && JSON.stringify(body),
		});
		const json = await response.json();

		// The cart stays as it was when the API refuses a change, e.g. a
		// quantity which is not in stock
		if (response.ok) {
			this.cart = json.data;
		} else {
			alert(json.message);
		}

		document.querySelectorAll('.cart-count').forEach((element) => {
			element.textContent = this.cart?.quantity ? `(${this.cart.quantity})` : '';
		});

		return this.cart;
	}

	/** Renders the whole cart: a row for every product and the totals */
	render(cart) {
		const { label } = this;
		const products = cart?.products?.items ?? [];

		if (products.length === 0) {
			this.innerHTML = `<p>${label.empty}</p>`;
			return;
		}

		// The same table as in /site/snippets/cart.php
		this.innerHTML = `
			<table>
				<thead>
					<tr>
						<th>${label.product}</th>
						<td>${label.quantity}</td>
						<td>${label.price}</td>
						<td>${label.total}</td>
					</tr>
				</thead>
				<tbody></tbody>
				<tfoot></tfoot>
			</table>
		`;
		this.querySelector('tbody').append(...products.map((item) => this.renderItem(item)));
		this.renderTotals(cart);
	}

	/** Renders the row of a product and makes its quantity field and remove button work */
	renderItem(item) {
		const { label } = this;
		const row = document.createElement('tr');

		row.innerHTML = `
			<th>
				<a href="${item.url}">
					${item.thumb ? `<img src="${item.thumb.src}" srcset="${item.thumb.srcset}" sizes="46px" alt="${item.thumb.alt}">` : ''}
					${item.title}
				</a>
			</th>
			<td>
				<input type="number" value="${item.quantity}" min="1" aria-label="${label.quantity}">
				<button>${label.remove}</button>
			</td>
			<td>${amount(item.price)}</td>
			<td>${amount(item.total)}</td>
		`;

		// A new quantity only updates the amounts. The row itself stays, so
		// the field keeps the focus while the visitor changes it.
		row.querySelector('input').addEventListener('change', async ({ target: input }) => {
			const { value } = input;
			const cart = await this.request('PATCH', { key: item.key, quantity: value });
			const product = cart.products?.items.find(({ key }) => key === item.key);

			// Changed again in the meantime: the newer request shows the newer cart
			if (input.value !== value) {
				return;
			}
			// A quantity of 0 removes the product
			if (!product) {
				this.render(cart);
				return;
			}

			input.value = product.quantity;
			row.cells[2].innerHTML = amount(product.price);
			row.cells[3].innerHTML = amount(product.total);
			this.renderTotals(cart);
		});

		row.querySelector('button').addEventListener('click', async () => {
			this.render(await this.request('DELETE', { key: item.key }));
		});

		return row;
	}

	/** Renders shipping, total and taxes */
	renderTotals(cart) {
		const { label } = this;

		this.querySelector('tfoot').innerHTML = `
			<tr>
				<th colspan="3">${label.shipping}</th>
				<td>${cart.shippings ? amount(cart.shippings.total) : label.free}</td>
			</tr>
			<tr>
				<th colspan="3">${label.total}</th>
				<td>${cart.total.price}</td>
			</tr>
			${cart.taxRates.map((tax, index) => `
				<tr class="text-s color-gray-500">
					${index === 0 ? `
						<th colspan="3" rowspan="${cart.taxRates.length}">${label.vat}</th>
					` : ''}
					<td class="vat"><span>${tax.rate}</span> ${tax.price}</td>
				</tr>
			`).join('')}
			${this.popover ? `
				<tr>
					<td colspan="4">
						<a class="button-white" href="${cart.checkout.url}">${cart.checkout.title}</a>
					</td>
				</tr>
			` : ''}
		`;
	}
});
