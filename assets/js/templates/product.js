class Product {
  constructor(element) {
    this.buttonAddToCart = element.querySelector('button[data-action="add-to-cart"]');
    this.cart = window.cart;

    this.buttonAddToCart?.addEventListener('click', async (event) => {
      event.stopPropagation();
      event.target.classList.add('-loading');
      await window.cart.add(element.dataset.id);
      window.cart.element.setAttribute('open', '');
      window.cart.element.scrollIntoView({
        behavior: 'smooth',
      });
      event.target.classList.remove('-loading');
    });
  }
}

document.querySelectorAll('.product').forEach((productElement) => new Product(productElement));
