import {Item} from '../modules/result.js';

export class User extends Item {
    constructor(data) {
        if (data instanceof Item) return data;

        super(data);
        this.username = null;
        this.password = null;
        this.email = null;
        this.first_name = null;
        this.last_name = null;

        Object.assign(this, data);
    }
}