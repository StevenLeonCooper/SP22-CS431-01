import {Item} from '../modules/result.js';

export class Login extends Item {
    constructor(data) {
        if (data instanceof Item) return data;

        super(data);
        this.username = null;
        this.password = null;

        Object.assign(this, data);
    }
}