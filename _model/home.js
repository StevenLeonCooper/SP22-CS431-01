import {Item} from '../modules/result.js';

export class Home extends Item {
    constructor(data) {
        if (data instanceof Home) return data;

        super(data);
        this.title = null;
        this.greeting = null;

        Object.assign(this, data);
    }
}