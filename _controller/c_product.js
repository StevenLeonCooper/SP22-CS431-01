import {Model, View, Controller} from '../modules/controller.js';
import {ProductList} from '../_model/product.js';

const productModel = new Model("product");
const productView = new View("product");
const productController = new Controller(productView, productModel);

// This is an IIFE (Immediately Invoking Function Expression) which runs on startup.
(async () => {
    await productController.setup();

    // Get products from the model.
    let data = productModel.export();
    let products = new ProductList(data);

    // Display products in the view.
    console.log(products.status);
    //if(products.status.includes("OK")) {
        productView.render(products);
    //}

    //TODO: Add event listeners here for inspecting items, making a purchase, etc.

})();