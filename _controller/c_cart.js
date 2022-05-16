import {Model, View, Controller} from '../modules/controller.js';
import {Cart} from '../_model/cart.js';

const cartModel = new Model(Cart);
const cartView = new View("cart");
const cartController = new Controller(cartView, cartModel);

// This is an IIFE (Immediately Invoking Function Expression) which runs on startup.
(async () => {
    if(window.user["visitor"]) {
        window.location = "login";
    }

	console.log("Hello from shopping cart!!");
    await cartController.setup();

    // Get cart page data from the model.
    let cart = cartController.model.list;


    // // Display cart page data in the view.
    console.log(`cart.status == ${cart.status}`);
    if(cart.status.includes("OK")) {
        cart.totalCost=0;
        cart.items.forEach(x => cart.totalCost += parseFloat(x.totalPrice));
        cartView.render(cart);
    }

    document.addEventListener("change", async(e) => {
        if (e.target.dataset.action == "updateQuantity") {
            let update = {
                'id': e.target.dataset.id,
                'quantity': e.target.value
            }
            console.log(update);
            let result = await cartModel.put(update);

            if(result.status.includes("OK")){
                cartView.render(productModel.data);
            }
        }
    });

    //TODO: Add event listeners here

})();