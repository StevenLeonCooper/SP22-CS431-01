// The base class for modules. Its main goal is getting/posting data to and from the API.
export class Model {

    constructor(name) {
        // The main purpose of this line is to allow VScode to know for sure that the returned
        // object is a Model, which allows it to give Intellisense tooltips.
        if (name instanceof Model) return name;

        this.name = name;
        this.data = null;
        this.dataUrl = `_data/${name}.json`;
    }

    // This function gets called on startup by the controller module 
    // in order to populate the data field.
    async setup() {
        await this.get(this.dataUrl);
    }

    // Makes a get request to the API for data at the specified url.
    async get(url) {
        let request = await fetch(url);
        let jsonData = await request.json();
        this.data = jsonData;
    }

    // Make a post request to the API to update data at the specified url.
    async post(url) {
        //TODO
    }
}