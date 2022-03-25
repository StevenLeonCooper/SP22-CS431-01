import mustache from './mustache.js';

export class View {

    constructor(name, settings) {
        if(name instanceof View) return name;

        this.name = name;
        this.url = settings ? settings.url : `_view/${this.name}.html` // URL where we send a request for the HTML.
        this.template = ""; // This will hold the actual HTML once we load it in setup().
        this.selector = settings ? settings.selector : `#view_${name}`; // Where we should place the view on the page.
    }

    // Downloads the HTML.
    async setup() {
        // This line makes the actual HTTP request for the HTML file.
        // See https://developer.mozilla.org/en-US/docs/Web/API/fetch for details on the fetch() method.
        let request = await fetch(this.url);

        // See https://developer.mozilla.org/en-US/docs/Web/API/Request/text for details on Request.text() method.
        let template = await request.text();
        
        this.template = template;
    }

    // Writes the HTML onto the webpage along with data from the model.
    render(modelData){
        let html = mustache.render(this.template, modelData);
        document.querySelector(this.selector).innerHTML = html;
    }

    // Renders a modal popup on the page.
    renderModal(data) {
        let html = mustache.render(this.template, data);
        document.body.insertAdjacentHTML("beforeend", html);
    }
}

export class PartialView extends View {
    constructor(name, settings) {
        super(name, {
            url: `_view/_partial/${name}.html`,
            selector: `#partial_${name}`
        });
        Object.assign(this, settings);
    }
}