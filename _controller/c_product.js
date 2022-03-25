import {Model, View, Controller} from '../modules/controller.js';
import {Product} from '../_model/product.js';

const productModel = new Model(Product);
const productView = new View("product");
const productController = new Controller(productView, productModel);

// This is an IIFE (Immediately Invoking Function Expression) which runs on startup.
(async () => {
    await productController.setup();

    let productList = productController.model.list;

    // Display products in the view.
    console.log(productList);
    if(productList.status.includes("OK")) {
        productView.render(productList);
    }

    //TODO: Add event listeners here for inspecting items, making a purchase, etc.

    document.getElementById("APF").addEventListener("submit", async (e) => {
        e.preventDefault()

        let form_data = productController.formData(e.currentTarget);
        let newProduct = new Product(form_data);

        let request = await productModel.post(newProduct);
    
        if(request.status.includes("OK")){
            alert("It Worked!");
            productView.render(productModel.data);
        }
    })
    
})();