/**
 * The models of the Merx API in their default view
 * Merx 2.0.0-alpha.11, https://merx.wagnerwagner.de/reference/api/models
 */

/** @see https://merx.wagnerwagner.de/reference/api/models/cart */
export interface Cart {
	products: { items: ListItem[]; total: Price } | null;
	total: Pick<Price, 'price' | 'priceRaw' | 'priceNet' | 'priceNetRaw'> | null;
	taxRates: Tax[];
	quantity: number;
	shippings: { items: ListItem[]; total: Price } | null;
	discounts: { items: ListItem[]; total: Price } | null;
	isFromPrice: boolean;
	isOrderable: boolean;
	checkout: { title: string; url: string } | null;
}

/** @see https://merx.wagnerwagner.de/reference/api/models/list-item */
export interface ListItem {
	key: string;
	title: string | null;
	type: string;
	thumb: { alt: string; src: string; srcset: string } | null;
	url: string | null;
	price: Price | null;
	total: Price | null;
	quantity: number;
	data: Record<string, unknown> | null;
	page: { url: string; template: string; uuid: string | null } | null;
}

/** @see https://merx.wagnerwagner.de/reference/api/models/price */
export interface Price {
	price: string;
	priceRaw: number;
	priceNet: string;
	priceNetRaw: number | null;
	taxIncluded: boolean | null;
	tax: Tax | null;
}

/** @see https://merx.wagnerwagner.de/reference/api/models/product-list */
export interface ProductList {
	products: { items: ListItem[]; total: Price } | null;
	total: Pick<Price, 'price' | 'priceRaw' | 'priceNet' | 'priceNetRaw'> | null;
	taxRates: Tax[];
	quantity: number;
	shippings: { items: ListItem[]; total: Price } | null;
	discounts: { items: ListItem[]; total: Price } | null;
	isFromPrice: boolean;
	isOrderable: boolean;
}

/** @see https://merx.wagnerwagner.de/reference/api/models/tax */
export interface Tax {
	rate: string;
	rateRaw: number | null;
	price: string | null;
	priceRaw: number | null;
}
