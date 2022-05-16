import {Model, View, Controller, PartialView} from '../modules/controller.js';
import {Cart} from '../_model/cart.js';

const cartModel = new Model(Cart);
const cartView = new View("cart");
const cartController = new Controller(cartView, cartModel);

// This is an IIFE (Immediately Invoking Function Expression) which runs on startup.
(async () => {
    if(window.user["visitor"]) {
        window.location = "login";
    }

	// console.log("Hello from shopping cart!!");
    await cartController.setup();

    // Get cart page data from the model.
    let cart = cartController.model.list;

	let closeModal = function(e){ 
        let wrapper = document.getElementById("modal-wrapper");
        wrapper.remove();
    }

    // // Display cart page data in the view.
    console.log(`cart.status == ${cart.status}`);
    if(cart.status.includes("OK")) {
        cart.totalCost=0;
        cart.items.forEach(x => x.totalPrice = parseFloat(x._totalPrice()));
        cart.items.forEach(x => cart.totalCost += parseFloat(x._totalPrice()));
        cartView.render(cart);
    }

    document.addEventListener("change", async(e) => {
        if (e.target.dataset.action == "updateQuantity") {
            let update = {
                'id': e.target.dataset.id,
                'quantity': e.target.value
            };
            
            let result = await cartModel.put(update);
			console.log(result);

            if(result.status.includes("OK")){
				console.log("OK from c_cart");
				await cartModel.importData();
				let cart = cartController.model.list;
				cart.totalCost=0;
				cart.items.forEach(x => x.totalPrice = parseFloat(x._totalPrice()));
				cart.items.forEach(x => cart.totalCost += parseFloat(x._totalPrice()));
                cartView.render(cart);
            }
        }
    });

	document.addEventListener("click", async(e) => {
		if (e.target.dataset.action == "removeFromCart") {
			let id = e.target.dataset.id;
			// console.log(toDelete.id);
			let result = await cartModel.delete(id);
			// console.log(result);

			if(result.status.includes("OK")) {
				await cartModel.importData();
				let cart = cartController.model.list;
				cart.totalCost=0;
				cart.items.forEach(x => x.totalPrice = parseFloat(x._totalPrice()));
				cart.items.forEach(x => cart.totalCost += parseFloat(x._totalPrice()));
                cartView.render(cart);
			}
		}
		if (e.target.dataset.action == "clearCart") {
			let result = await cartModel.delete();
			console.log(result);

			if(result.status.includes("OK")) {
				await cartModel.importData();
				let cart = cartController.model.list;
				cart.totalCost=0;
				cart.items.forEach(x => x.totalPrice = parseFloat(x._totalPrice()));
				cart.items.forEach(x => cart.totalCost += parseFloat(x._totalPrice()));
                cartView.render(cart);
			}
		}
		if (e.target.dataset.action == "completePurchase") {
			// let result = await cartModel.delete();
			// console.log(result);

			// if(result.status.includes("OK")) {
			// 	await cartModel.importData();
			// 	let cart = cartController.model.list;
			// 	cart.totalCost=0;
			// 	cart.items.forEach(x => x.totalPrice = parseFloat(x._totalPrice()));
			// 	cart.items.forEach(x => cart.totalCost += parseFloat(x._totalPrice()));
            //     cartView.render(cart);
			// }

			console.log("buying");

			let partial = new PartialView("receipt");
            await partial.setup();

			// let productId = e.target.dataset.productId;
			// let result = await cartModel.get(); 
			
            // let product = new Product(result);

            partial.renderModal(cart);

            document.getElementById("closeModal").addEventListener("click", closeModal);

			document.getElementById('clearCartButton').click();
		}
	});

    //TODO: Add event listeners here

})();