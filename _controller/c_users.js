import { Model, View, Controller } from '../modules/controller.js';
import { User } from '../_model/user.js';

const userModel = new Model(User);
const userView = new View("users");
const userController = new Controller(userView, userModel);

(async () => {
    if(window.user["visitor"]) {
        window.location = "login";
    }

    console.log(window.user);

    await userController.setup();

    let userList = userController.model.list;
    
    if(window.user.role == "user") {
        userList.user = true;
    }
    else if(window.user.role == "admin") {
        userList.admin = true;
    }

       // Display users in the view.
       console.log(userList);
       if(userList.status.includes("OK")) {
           userController.view.render(userList);
       }       
})();