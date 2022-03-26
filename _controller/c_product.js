import {Model, View, PartialView, Controller} from '../modules/controller.js';
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

    let closeModal = function(e){ 
        let wrapper = document.getElementById("modal-wrapper");
        wrapper.remove();
    }

    //TODO: Add event listeners here for inspecting items, making a purchase, etc.
    document.addEventListener("click", async (e) => {
        if(e.target.id == "AddProductButton") {
            document.getElementById("AddProductForm").classList.toggle("hidden");
        }
        if(e.target.dataset.action == "viewProduct") {
            			
            let partial = new PartialView("product-modal");
            await partial.setup();

			let productId = e.target.dataset.productId;
			let result = await productModel.get("api/product/?id=" + productId); 
			
            let product = new Product(result);

            console.log(product);
            
            partial.renderModal(product);

            document.getElementById("closeModal").addEventListener("click", closeModal);
        }
    })

    document.addEventListener("submit", async (e) => {

        if(e.target.dataset.action == "addNewProduct") {
            e.preventDefault()

            let form_data = productController.formData(e.currentTarget);
            let newProduct = new Product(form_data);

            let result = await productModel.post(newProduct);
            newProduct = new Product(result);
        
            if(result.status.includes("OK")){
                productModel.add(newProduct);
                productView.render(productModel.data);
            }
        }
    })

})();