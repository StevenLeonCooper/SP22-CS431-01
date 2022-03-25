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
    document.getElementById("AddProductButton").addEventListener("click", () => {
        document.getElementById("AddProductForm").classList.toggle("hidden");
    })
   
    document.getElementById("APF").addEventListener("submit", async (e) => {
        e.preventDefault()

        let form_data = productController.formData(e.currentTarget);
        let newProduct = new Product(form_data);

        let request = await productModel.post(newProduct);
    
        if(request.status.includes("OK")){
            productModel.add(newProduct);
            productView.render(productModel.data);
        }
    });

	Array.from(document.querySelectorAll(".product-card")).forEach((card) => {
		// console.log(card);
		card.addEventListener("click", async (e) => {
			let dpi = document.getElementById("DetailedProductInfo");
			let productId = card.dataset.product-id;
			console.log(productId);

			//Fill in the correct info
<<<<<<< HEAD
			// dpi.querySelector("#dpi-title").innerText = ;
=======
			//dpi.querySelector("#dpi-title").innerText = ;
>>>>>>> 89b0f8aba9a31a8ad5c0e5be42c7e99a1198023c

			//Make the pop-up visible, change back to none to hide
			dpi.style.display = "block"; 
		});
	});


})();