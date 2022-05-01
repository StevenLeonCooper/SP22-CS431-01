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

    document.addEventListener("click", async (e) => {
        if(e.target.dataset.action == "addNewUserButton") {
            e.preventDefault();
            document.getElementById("create_form").classList.toggle("hidden");
        }

        if(e.target.dataset.action == "deleteUser") {
            e.preventDefault();

            let user_id = e.target.dataset.id
            let result = await userModel.delete(user_id);

            userModel.remove(user_id);
            addUserRole(userModel.data);
            userView.render(userModel.data);
        }
    });

    document.addEventListener("submit", async (e) => {

        e.preventDefault();

        if(e.target.dataset.action == "createUser") {
        
            let form_data = userController.formData(e.target);
            let newUser = new User(form_data);

            let result = await userModel.post(newUser);
            newUser = new User(result);
        
            if(result.status.includes("OK")){
                userModel.add(newUser);
                addUserRole(userModel.data);
                userView.render(userModel.data);
            }
        }

        if(e.target.dataset.action == "updateUser") {

            let form_data = userController.formData(e.target);
            let changedUser = new User(form_data);

            let result = await userModel.put(changedUser);
            changedUser = new User(result);

            if(result.status.includes("OK")) {
                userModel.update(changedUser);
                addUserRole(userModel.data);
                userView.render(userModel.data);
            }
        }

    });
       
})();