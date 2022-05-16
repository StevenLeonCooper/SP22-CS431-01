import {Item} from '../modules/result.js';

export class Cart extends Item {
    constructor(data) {
        if (data instanceof Cart) return data;

        super(data);
        this.product = 0;
		this.quantity = 0;
        this.price = 0;

        Object.assign(this, data);
    }
    get _price() {
        return parseFloat(this.price);
    }

    set _price(value){
        this.price = value;
    }

    get _quantity() {
        return parseInt(this.quantity);
    }

    set _quantity(value) {
        this.quantity= value;
    }

    _totalPrice() {
        return (this.price * this.quantity).toFixed(2);
    }
}