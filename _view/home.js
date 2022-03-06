import { Model, View, Controller } from '../modules/controller.js'

const homeModel = Model("homePage");
const homeView = View("homePage");
const homeController = Controller(homeModel, homeView);

(async () => {
	try {
		let myVar = await homeController.setup();
		homeView.render(homeModel.data);
	} catch (error) {
		console.error(error);
	}
})();
