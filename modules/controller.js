import {Model} from './model.js';
import {View} from './view.js';

// Exporting here allows us to reference model and view in individual controllers.
export {Model, View};

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
}