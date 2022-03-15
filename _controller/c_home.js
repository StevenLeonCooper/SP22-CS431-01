import {Model, View, Controller} from '../modules/controller.js';

const homeModel = new Model("product");
const homeView = new View("product");
const homeController = new Controller(homeView, homeModel);

// This is an IIFE (Immediately Invoking Function Expression) which runs on startup.
// See https://flaviocopes.com/javascript-iife/ for details.
(async () => {
    await homeController.setup();

    // Get home page data from the model.
    let data = homeModel.export();
    let home = new Home(data);

    // Display home page data in the view.
    if(home.status.includes(OK)) {
        homeView.render(home);
    }

    //TODO: Add event listeners here for any actions we might want users to be able to do on the home page.

})();