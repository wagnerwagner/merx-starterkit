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
		function updatePaymentMethods(paymentMethodInputElement) {
			const paymentMethod = paymentMethodInputElement.value;

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
		}

		// INITS
		// Init Stripe
		const stripePublishableKey = element.querySelector('input[name="stripePublishableKey"]')?.value ?? null;
		const stripeStyle = {
			base: {
				fontFamily: getComputedStyle(document.documentElement).getPropertyValue('--font-family-sans'),
				fontSize: '16px',
			},
		};

		// check if stripePublishableKey is null or empty
		if (stripePublishableKey) {
			/** @type {Stripe} */
			this.stripe = Stripe(stripePublishableKey);
			/** @type {StripeElements} */
			this.stripeElements = this.stripe.elements({
				locale: document.querySelector('html').lang,
			});

			if (document.getElementById('stripe-card')) {
				// Create Stripe Card Element
				// https://stripe.com/docs/js/elements_object/create_element
				const stripeCardElement = this.stripeElements.create('card', {
					hidePostalCode: true,
					style: stripeStyle,
					disableLink: true,
				});
				// https://stripe.com/docs/js/element/mount
				stripeCardElement.mount('#stripe-card');
			}

			if (document.getElementById('stripe-iban')) {
				// Create Stripe IBAN Element
				// https://stripe.com/docs/js/elements_object/create_element
				const stripeIbanElement = this.stripeElements.create('iban', {
					supportedCountries: ['SEPA'],
					style: stripeStyle,
				});
				// https://stripe.com/docs/js/element/mount
				stripeIbanElement.mount('#stripe-iban');
			}

			if (document.getElementById('stripe-ideal')) {
				// Create Stripe IDEAL
				// https://stripe.com/docs/js/elements_object/create_element
				const stripeIbanElement = this.stripeElements.create('ideal', {
					style: stripeStyle,
				});
				// https://stripe.com/docs/js/element/mount
				stripeIbanElement.mount('#stripe-ideal');
			}
		}

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
		const { stripe, formElement } = this;
		const paymentMethod = formElement.elements.namedItem('paymentMethod')?.value ?? null;
		const formData = new FormData(formElement);

		const paymentGateway = formElement.querySelector(`[name="paymentMethod"][value="${paymentMethod}"]`).dataset.gateway;
		formData.set('paymentGateway', paymentGateway);

		event.preventDefault();
		this.loading = true;

		// Remove error elements
		formElement.querySelectorAll('.error').forEach((errorElement) => errorElement.remove());

		try {
			const clientSecret = await this.getClientSecret();

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
						({ error } = await stripe.confirmCardPayment(clientSecret, {
							payment_method: {
								card: this.stripeElements.getElement('card'),
								billing_details: {
									name: formElement.name.value,
									email: formElement.email.value,
								},
							},
						}));
					} else if (paymentMethod === 'sepa-debit') {
						({ error } = await stripe.confirmSepaDebitPayment(clientSecret, {
							payment_method: {
								sepa_debit: this.stripeElements.getElement('iban'),
								billing_details: {
									name: formElement.name.value,
									email: formElement.email.value,
								},
							},
						}));
					} else if (paymentMethod === 'klarna') {
						({ error } = await stripe.confirmKlarnaPayment(clientSecret, {
							return_url: json.redirectUrl,
							payment_method: {
								billing_details: {
									name: formElement.name.value,
									email: formElement.email.value,
								},
							}
						}));
					} else if (paymentMethod === 'ideal') {
						({ error } = await stripe.confirmIdealPayment(clientSecret, {
							return_url: json.redirectUrl,
							payment_method: {
								ideal: this.stripeElements.getElement('ideal'),
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
