/* global Stripe */

class Checkout {
  constructor(element) {
    this.element = element;
    this.formElement = element.querySelector('#checkout-form');
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
      this.stripe = Stripe(stripePublishableKey);
      this.stripeElements = this.stripe.elements({
        locale: document.querySelector('html').lang,
      });

      if (document.getElementById('stripe-card')) {
        // Create Stripe Card Element
        // https://stripe.com/docs/js/elements_object/create_element
        const stripeCardElement = this.stripeElements.create('card', {
          hidePostalCode: true,
          style: stripeStyle,
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
  get clientSecret() {
    return fetch('/api/shop/client-secret')
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

    event.preventDefault();
    this.loading = true;

    // Remove error elements
    formElement.querySelectorAll('.error').forEach((errorElement) => errorElement.remove());

    try {
      // Some payment method need additional JavaScript
      if (paymentMethod === 'credit-card-sca' || paymentMethod === 'sepa-debit') {
        let source = null;
        let error = null;
        // Optional: Add billing details like name, postal code or city to the stripe request.

        if (paymentMethod === 'credit-card-sca') {
          const clientSecret = await this.clientSecret;
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
          // https://stripe.com/docs/js/tokens_sources/create_source
          ({ source, error } = await stripe.createSource(this.stripeElements.getElement('iban'), {
            type: 'sepa_debit',
            currency: 'eur',
            owner: {
              name: formElement.name.value,
              email: formElement.email.value,
            },
          }));
          // add stripe token to formData
          formData.append('stripeToken', source?.id ?? null);
        }
        if (error) {
          throw error;
        }
      }

      // submit form
      await fetch(formElement.action, {
        body: formData,
        method: 'POST',
        headers: {
          'x-language': document.querySelector('html').lang,
        },
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === 201) {
            window.location = data.redirect;
          } else if (data.key === 'error.merx.fieldsvalidation') {
            this.createFieldErrors(data.details);
          } else {
            throw data;
          }
        });
    } catch (error) {
      this.paymentMethodsElement.appendChild(this.createErrorElement(error.message));
    }
    this.loading = false;
  }
}

document.querySelectorAll('.checkout').forEach((productElement) => new Checkout(productElement));
