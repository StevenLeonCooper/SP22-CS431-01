import { Item, ItemList, Result } from "./result.js";

const Settings = {
    apiUrl: function(apiName){
        return `_data/${apiName}.json`; // Links to dummy test data until PHP API is finished.
    }
};

// The base class for models. Its main goal is getting/posting data to and from the API.
export class Model {

    constructor(itemType) {
        // This line allows lets VScode know for sure that the returned object is a Model, which allows it to give Intellisense tooltips.
        if (itemType instanceof Model) return itemType;

        let item = new Item(itemType);

        this.data = null;
        this.dataUrl = Settings.apiUrl(item.apiName); 
        this.itemType = itemType;
        this.items = new ItemList();
    }

    get list() {
        return new ItemList(this.data, this.itemType);
    }

    get item() {
        return new this.itemType(this.data);
    }

    // This function gets called on startup by the controller module in order to populate the data field.
    async importData() {
        let result = await this.get();
        this.data = result;
    }

    // Makes a get request to the API for data at the specified url.
    async get(url) {
        url = url ?? this.dataUrl;
        let response = await fetch(url);
        let jsonData = await response.json();
        return new Result(jsonData);
    }

    // Make a post request to the API to update data at the specified url.
    async post(url) {
        //TODO
    }
}