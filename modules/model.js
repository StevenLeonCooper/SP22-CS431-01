import { Item, ItemList, Result } from "./result.js";

const Settings = {
    apiUrl: function(apiName){
        return `api/${apiName}`;
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
        //this.items = new ItemList();
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

    _encode(data) {
        if (!data) return "";
        return Object.keys(data).map(k => encodeURIComponent(k) + '=' + encodeURIComponent(data[k])).join('&');
    }

    _dataString(data) {
        let jsonData = JSON.stringify(data); // convert data to JSON
        return this._encode({ json: jsonData });
    }

    add(newItem) {
        let dataList = new ItemList(this.data, this.itemType);
        dataList.add(newItem);
        this.data = dataList;
    }

    update(changedItem) {
        let dataList = new ItemList(this.data, this.itemType);
        dataList.replace("id", changedItem.id, changedItem);
        this.data = dataList;
    }

    // Make a post request to the API to create data at the specified url.
    async post(data) {
        let bodytext = this._dataString(data)
        
        let response = await fetch(this.dataUrl, {
            method: "POST",
            headers: { "content-Type": "application/x-www-form-urlencoded" },
            body: bodytext
        });

        let jsonData = await response.json();
        return new Result(jsonData);
    }

    // Make a put request to the API to create data at the specified url.
    async put(data) {
        let bodytext = this._dataString(data)
        
        let response = await fetch(this.dataUrl, {
            method: "PUT",
            headers: { "content-Type": "application/x-www-form-urlencoded" },
            body: bodytext
        });

        let jsonData = await response.json();
        return new Result(jsonData);
    }
}