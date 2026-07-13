<?php /** @var Kirby\Cms\Page $page */ ?>
<?php /** @var Kirby\Cms\Site $site */ ?>
<?php /** @var Kirby\Cms\App $kirby */ ?>
<header class="header">
	<a href="<?= $site->url() ?>" class="logo">
		<?= $site->title() ?>
	</a>
	<div class="lang-switch">
		<?php foreach ($kirby->languages() as $language) : ?>
			<a href="<?= $page->url($language->code()) ?>" aria-current="<?= $language === $kirby->language() ? 'page' : 'false' ?>"><?= $language->name() ?></a>
		<?php endforeach; ?>
	</div>
	<?php if ($page->template()->name() === 'checkout'): ?>
		<a class="link-cart" href="<?= $site->checkoutPage()->url() ?>">
			<?= t('cart') ?> <span class="cart-count"></span>
		</a>
	<?php else: ?>
		<button class="button-cart" id="cart-button" data-action="toggle-cart" popovertarget="cart">
			<?= t('cart') ?> <span class="cart-count"></span>
		</button>
		<my-cart id="cart" theme="dark" popover anchor="cart-button">
			<?php
			/**
			* The cart is loaded asynchronously. This is why you can make use of Kirby’s page cache and
			* provide your users a very fast web shop.
			* The cart’s content is loaded by `assets/js/my-cart.js`.
			*/
			?>
		</my-cart>
	<?php endif; ?>
	<nav class="header-nav">
		<?php foreach ($site->children()->listed() as $item) : ?>
			<a href="<?= $item->url() ?>" aria-current="<?= $item->isOpen() ? 'page' : 'false' ?>"><?= $item->title() ?></a>
		<?php endforeach ?>
	</nav>
</header>
