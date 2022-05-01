import {Item} from '../modules/result.js';

export class User extends Item {
    constructor(data) {
        if (data instanceof Item) return data;

        super(data);
        this.username = null;
        this.password = null;
        this.email = null;
        this.name_first = null;
        this.name_last = null;

        Object.assign(this, data);
    }
}