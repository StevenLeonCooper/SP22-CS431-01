import {Item, ItemList} from '../modules/result.js';

export class Product extends Item {
    constructor(data) {
        if (data instanceof Product) return data;

        super(data);
        this.title = null;
        this.description = null;
        this.price = 0;

        Object.assign(this, data);
    }
}

export class ProductList extends ItemList {
    constructor(data) {
        if (data instanceof ProductList) return data;

        super(data);
        this.items = this.items ?? [];
        console.log(`products: ${this.items}`);

        this.convertList(Product);
    }
}