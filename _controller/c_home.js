import {Model, View, Controller} from '../modules/controller.js';
import {Home} from '../_model/home.js';

const homeModel = new Model("home");
const homeView = new View("home");
const homeController = new Controller(homeView, homeModel);

// This is an IIFE (Immediately Invoking Function Expression) which runs on startup.
(async () => {
    await homeController.setup();

    // Get home page data from the model.
    let data = homeModel.export();
    let home = new Home(data);

    // Display home page data in the view.
    console.log(home.status);
    //if(home.status.includes("OK")) {
        homeView.render(home);
    //}

    //TODO: Add event listeners here for any actions we might want users to be able to do on the home page.

})();