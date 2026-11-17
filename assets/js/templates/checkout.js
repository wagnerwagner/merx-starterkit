/* global Stripe */

/** @typedef {import("../../../node_modules/@stripe/stripe-js/dist/stripe-js/index").Stripe} Stripe */
/** @typedef {import("../../../node_modules/@stripe/stripe-js/dist/stripe-js/index").StripeElements} StripeElements */

class Checkout {
	/** @param {HTMLElement} element */
	constructor(element) {
		this.element = element;

		/** @type {HTMLFormElement} */
		this.formElement = element.querySelector('form#checkout-form');
		this.paymentMethodsElement = element.querySelector('.payment-methods');

		// ELEMENTS
		const paymentMethodInputElements = element.querySelectorAll('input[name="paymentMethod"]');
		const paymentMethodFieldElements = element.querySelectorAll('.field[data-payment-method]');
		const submitElement = element.querySelector('button[type="submit"]');

		// METHODS
		// Helper method to create a div
		this.createErrorElement = (message) => {
			const errorElement = document.createElement('div');
			errorElement.classList.add('error');
			errorElement.textContent = message;
			return errorElement;
		};
		this.createFieldErrors = (details) => {
			console.log(details);
			Object.keys(details).forEach((key) => {
				const fieldElement = element.querySelector(`.field[data-name="${key}"]`);
				Object.values(details[key].message).forEach((message) => {
					if (fieldElement) {
						fieldElement.appendChild(this.createErrorElement(message));
					} else {
						console.error(message);
					}
				});
			});
		};

		// FUNCTIONS
		const updatePaymentMethods = (paymentMethodInputElement) => {
			const paymentMethod = paymentMethodInputElement.value;

			// Stripe.js is loaded once a payment method of Stripe is chosen
			if (paymentMethodInputElement.dataset.gateway === 'stripe') {
				this.loadStripe();
			}

			if (paymentMethodInputElement.dataset.submitText) {
				submitElement.innerText = paymentMethodInputElement.dataset.submitText;
			} else {
				submitElement.innerText = submitElement.dataset.defaultSubmitText;
			}

			paymentMethodFieldElements.forEach((paymentMethodFieldElement) => {
				if (paymentMethod === paymentMethodFieldElement.dataset.paymentMethod) {
					paymentMethodFieldElement.removeAttribute('hidden');
				} else {
					paymentMethodFieldElement.setAttribute('hidden', '');
				}
			});
		};

		// INITS
		submitElement.dataset.defaultSubmitText = submitElement.innerText;

		// EVENT LISTENER
		paymentMethodInputElements.forEach((paymentMethodInputElement) => {
			if (paymentMethodInputElement.checked) {
				updatePaymentMethods(paymentMethodInputElement);
			}

			paymentMethodInputElement.addEventListener('change', (event) => {
				updatePaymentMethods(event.target);
			});
		});

		element.addEventListener('submit', this.onSubmit.bind(this));
	}

	/**
	 * Loads Stripe.js and mounts its Elements, once
	 *
	 * Only the payment methods of the `stripe` gateway need it. Other
	 * payment methods, e.g. prepayment, never load anything from Stripe.
	 */
	loadStripe() {
		this.stripeLoaded ??= new Promise((resolve, reject) => {
			const script = document.createElement('script');
			// The Stripe.js release of the Stripe API version Merx uses
			// https://docs.stripe.com/sdks/stripejs-versioning
			script.src = 'https://js.stripe.com/endive/stripe.js';
			script.addEventListener('load', resolve);
			script.addEventListener('error', reject);
			document.head.append(script);
		}).then(() => {
			const stripePublishableKey = this.element.querySelector('input[name="stripePublishableKey"]').value;
			const style = {
				base: {
					fontFamily: getComputedStyle(document.documentElement).getPropertyValue('--font-family-sans'),
					fontSize: '16px',
				},
			};

			/** @type {Stripe} */
			this.stripe = Stripe(stripePublishableKey);
			/** @type {StripeElements} */
			this.stripeElements = this.stripe.elements({
				locale: document.documentElement.lang,
			});

			// https://stripe.com/docs/js/elements_object/create_element
			if (document.getElementById('stripe-card')) {
				this.stripeElements.create('card', { hidePostalCode: true, style, disableLink: true }).mount('#stripe-card');
			}
			if (document.getElementById('stripe-iban')) {
				this.stripeElements.create('iban', { supportedCountries: ['SEPA'], style }).mount('#stripe-iban');
			}
		});

		return this.stripeLoaded;
	}

	/**
	 * Creates the PaymentIntent of the cart at Stripe and returns its client secret
	 */
	// eslint-disable-next-line class-methods-use-this
	getClientSecret() {
		return fetch('/api/shop/stripe-client-secret')
			.then((response) => response.json())
			.then((data) => {
				if (data.clientSecret) {
					return data.clientSecret;
				}
				throw data?.message;
			});
	}

	set loading(isLoading = true) {
		this.element.classList.toggle('is-loading', isLoading);
		this.element.querySelectorAll('button, fieldset, input, select, textarea').forEach((inputElement) => {
			if (isLoading === true) {
				inputElement.setAttribute('disabled', '');
			} else {
				inputElement.removeAttribute('disabled');
			}
		});
	}

	get loading() {
		return this.element.classList.contains('is-loading');
	}

	async onSubmit(event) {
		const { formElement } = this;
		const paymentMethod = formElement.elements.namedItem('paymentMethod')?.value ?? null;
		const formData = new FormData(formElement);

		const paymentGateway = formElement.querySelector(`[name="paymentMethod"][value="${paymentMethod}"]`).dataset.gateway;
		formData.set('paymentGateway', paymentGateway);

		event.preventDefault();
		this.loading = true;

		// Remove error elements
		formElement.querySelectorAll('.error').forEach((errorElement) => errorElement.remove());

		try {
			// Only a payment with Stripe needs a PaymentIntent
			let clientSecret = null;
			if (paymentGateway === 'stripe') {
				await this.loadStripe();
				clientSecret = await this.getClientSecret();
			}

			// submit form
			const response = await fetch(formElement.action, {
				body: formData,
				method: 'POST',
				headers: {
					'x-language': document.querySelector('html').lang,
					'accept': 'application/json',
				},
			})

			if (response.redirected === true) {
				// Redirect user to order page
				window.location.href = location;
			} else {
				const json = await response.json();

				if (json.status === 'redirect') {
					// Some payment method need additional JavaScript
					let error = null;
					// Optional: Add billing details like name, postal code or city to the stripe request.

					if (paymentMethod === 'card') {
						// https://stripe.com/docs/js/payment_intents/confirm_card_payment
						({ error } = await this.stripe.confirmCardPayment(clientSecret, {
							payment_method: {
								card: this.stripeElements.getElement('card'),
								billing_details: {
									name: formElement.name.value,
									email: formElement.email.value,
								},
							},
						}));
					} else if (paymentMethod === 'sepa-debit') {
						({ error } = await this.stripe.confirmSepaDebitPayment(clientSecret, {
							payment_method: {
								sepa_debit: this.stripeElements.getElement('iban'),
								billing_details: {
									name: formElement.name.value,
									email: formElement.email.value,
								},
							},
						}));
					} else if (paymentMethod === 'klarna') {
						({ error } = await this.stripe.confirmKlarnaPayment(clientSecret, {
							return_url: json.redirectUrl,
							payment_method: {
								billing_details: {
									name: formElement.name.value,
									email: formElement.email.value,
								},
							}
						}));
					} else if (paymentMethod === 'ideal') {
						({ error } = await this.stripe.confirmIdealPayment(clientSecret, {
							return_url: json.redirectUrl,
							payment_method: {
								// No bank: the customer picks it on the page Stripe redirects to
								ideal: {},
								billing_details: {
									name: formElement.name.value,
									email: formElement.email.value,
								},
							}
						}));
					}
					if (error) {
						throw error;
					}
					window.location = json.redirectUrl;
				} else if (json.key === 'error.merx.fieldsvalidation') {
					this.createFieldErrors(json.details);
				} else {
					throw json.message ?? 'Fatal error';
				}
			}
		} catch (error) {
			this.paymentMethodsElement.appendChild(this.createErrorElement(error.message ?? error));
		}
		this.loading = false;
	}
}

document.querySelectorAll('.checkout').forEach((productElement) => new Checkout(productElement));
