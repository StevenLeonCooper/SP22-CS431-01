import {Model} from './model.js';
import {View, PartialView} from './view.js';

// Exporting here allows us to reference model and view in individual controllers.
export {Model, View, PartialView};

// Base class for controllers. By itself, it does not do much.
export class Controller {
	constructor(view, model) {
		this.view = new View(view);
		this.model = new Model(model);
	}

	async setup() {
		await this.view.setup();
		await this.model.importData();
	}

	formData(form) {
        let formData = {};
        //Standard inputs with value attributes. 
        form.querySelectorAll("input:not([type='radio']):not([type='checkbox']), select, textarea")
            .forEach((el) => {
                let identity = el.id ? el.id : el.name;
                formData[identity] = el.value;
            });

        // Complex inputs with no simple "value" element to aggregate data. 
        form.querySelectorAll("input[type='radio']:checked, input[type='checkbox']:checked")
            .forEach((el) => {
                formData[el.name] = el.value;
            });
        return formData;
    }
}