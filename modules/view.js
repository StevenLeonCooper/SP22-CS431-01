import mustache from './mustache.js';

export class View {

    constructor(name) {
        if(name instanceof View) return name;

        this.name = name;
        this.url = `_view/${this.name}.html` //URL where we send a request for the HTML.
        this.template = ""; // This will hold the actual HTML once we load it in setup().
        this.selector = `#view_${name}`; // Where we should place the view on the page.
    }

    //Downloads the HTML.
    async setup() {
        // This line actually makes the HTTP request to the API.
        let request = await fetch(this.url);
        let template = await request.text();
        this.template = template;
    }

    // Writes the HTML onto the webpage along with data from the model.
    render(modelData){
        let html = mustache.render(this.template, modelData);
        document.querySelector(this.selector).innerHTML = html;
    }
}