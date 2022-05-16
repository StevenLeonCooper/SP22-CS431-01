import {Item} from '../modules/result.js';

export class Cart extends Item {
    constructor(data) {
        if (data instanceof Cart) return data;

        super(data);
        this.product = 0;
		this.quantity = 0;
        this.price = 0;
        this._quantity = 0;
        this._totalPrice = 0;

        Object.assign(this, data);
    }
    get price() {
        return parseFloat(this._price);
    }

    set price(value){
        this._price = value;
    }

    get quantity() {
        return parseInt(this._quantity);
    }

    set quantity(value) {
        this._quantity= value;
    }

    get totalPrice() {
        return (this.price * this.quantity).toFixed(2);
    }
}