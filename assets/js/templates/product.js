const cart = document.querySelector('my-cart');

document.querySelectorAll('.product-form').forEach((productForm) => {
	productForm.addEventListener('submit', async (/** @type SubmitEvent */ event) => {
		event.preventDefault();
		event.submitter.toggleAttribute('data-loading', true);
		await cart.addItem(productForm);
		event.submitter.toggleAttribute('data-loading', false);
	});
});
