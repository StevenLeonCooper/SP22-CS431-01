import { Model, View, Controller } from '../modules/controller.js';
import { User } from '../_model/user.js';

const userModel = new Model(User);
const userView = new View("users");
const userController = new Controller(userView, userModel);

(async () => {
    if(window.user["visitor"]) {
        window.location = "login";
    }

    await userController.setup();

    let userList = userController.model.list;
    
    let addUserRole = function(objToAddTo){   
        if(window.user.role == "user") {
            objToAddTo.user = true;
        }
        else if(window.user.role == "admin") {
            objToAddTo.admin = true;
        } 
    }

    
    addUserRole(userList);

    // Display users in the view.
    if(userList.status.includes("OK")) {
        userController.view.render(userList);
    }

    document.addEventListener("submit", async (e) => {

        e.preventDefault();

        if(e.target.dataset.action == "updateUser") {

            let form_data = userController.formData(e.target);
            let changedUser = new User(form_data);

            let result = await userModel.put(changedUser);
            changedUser = new User(result);
            console.log(changedUser);

            if(result.status.includes("OK")) {
                userModel.update(changedUser);
                addUserRole(userModel.data);
                userView.render(userModel.data);
            }
        }

    });
       
})();