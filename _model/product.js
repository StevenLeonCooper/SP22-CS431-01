import {Item} from '../modules/result.js';

export class Product extends Item {
    constructor(data) {
        if (data instanceof Product) return data;

        super(data);
        this.title = null;
        this.description = null;
        this.price = 0.00;
        this.image_url = null;

        Object.assign(this, data);
    }
}