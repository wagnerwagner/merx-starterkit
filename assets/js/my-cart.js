customElements.define('my-cart', class extends HTMLElement {
	/** @see https://merx.wagnerwagner.de/reference/api/models/cart */
	#data = null;

	#fetchController = null;

	endpoint = '/api/shop/cart';

	set data(value) {
		if (value !== this.#data) {
			this.#data = value;
			this.render();
		}
	}

	get data() {
		return this.#data;
	}

	show() {
		this.showPopover();
	}

	fetch(method = 'get', /** @type Object */ body = null) {
		return new Promise(async (resolve, reject) => {
			try {
				this.#fetchController?.abort();
				this.#fetchController = new AbortController();

				const { lang } = document.documentElement;
				const response = await fetch(this.endpoint, {
					method,
					headers: {
						'x-language': lang,
					},
					body: body && method !== 'get' ? JSON.stringify(body) : null,
					signal: this.#fetchController.signal,
				});
				const json = await response.json();
				if (json.status === 'ok') {
					this.data = json.data;
					resolve(this.data);
				} else {
					throw new Error(json.message);
				}
			} catch (/** @type Error */ error) {
				// Error 20 is AbortError an comes from this.#fetchController.abort().
				if (error.name !== 'AbortError') {
					console.error(error);
					alert(error.message);
					reject(error);
				} else {
					resolve(null);
				}
			}
		});
	}

	addItem(/** @type HTMLFormElement */ productForm) {
		const formData = new FormData(productForm);
		const object = {};
		formData.forEach((value, key) => object[key] = value);
		return this.fetch('post', object).then(() => this.show());
	}

	updateItem(key, quantity) {
		return this.fetch('patch', {
			key,
			quantity,
		});
	}

	removeItem(/** @type string */ key) {
		return this.fetch('delete', {
			key,
		});
	}

	async connectedCallback() {
		this.fetch();
		this.addEventListener('click', (event) => {
			if (event.target.matches('[data-action="remove"]')) {
				this.removeItem(event.target.value);
			}
		});
	}

	handleQuantityChange(/** @type Event */ event) {
		const { target } = event
		return this.updateItem(target.dataset.key, target.value);
	}

	render() {
		const items = this.data?.products?.items ?? null;
		this.innerHTML = `
			${items ? `
				<ul>
					${items.map(item => `
						<li class="cart-item">
							${item.thumb ? `<img src="${item.thumb.src}" srcset="${item.thumb.srcset}" alt="${item.thumb.alt}" sizes="40px">` : ''}
							<strong>${item.title}</strong>
							<input type="number" data-key="${item.key}" name="quantity" min="1" value="${item.quantity}">
							<button name="remove" value="${item.key}">Remove</button>
							<em>${item.total.price}</em>
							${item.quantity > 1 ? `<small>${item.quantity} × ${item.price.price}</small>` : ''}
						</li>
				`).join('')}
				<p>Total: ${this.data?.total.price}</p>
				<a href="${this.data?.checkout?.url}">${this.data?.checkout?.title ?? 'Buy'}</a>
			` : 'Empty cart'}
			</ul>
		`;

		this
			.querySelectorAll('input[name="quantity"]')
			.forEach((_) => {
				_.addEventListener('change', (event) => {
					this.handleQuantityChange(event);
				});
				_.addEventListener('input', (event) => {
					this.handleQuantityChange(event);
				});
			});
		this
			.querySelectorAll('button[name="remove"]')
			.forEach((_) => _.addEventListener('click', (event) => {
				this.removeItem(event.target.value);
			}));
	}
});
