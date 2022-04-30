import {Model, View, PartialView, Controller} from '../modules/controller.js';
import { User } from '../_model/user.js';
import { Login } from '../_model/login.js';

const loginModel = new Model(Login);
const loginView = new View("login");
const loginController = new Controller(loginView, loginModel);

// This is an IIFE (Immediately Invoking Function Expression) which runs on startup.

(async () => {
    await loginController.setup();

    loginView.render({});

    const urlParams = new URLSearchParams(window.location.search);

    let logoutRequest = urlParams.get('logout') ?? false;
    //let loggedOut = urlParams.get('logged_out') ?? false;

    if (logoutRequest) {

        let result = await loginModel.delete("");

        window.location = "login?logged_out=1";
    }

    document.addEventListener("click", async (e) => {
        if(e.target.dataset.action == "New User Registration") {
            e.preventDefault();
            document.getElementById("reg_area").classList.toggle("hidden");
        }
    });

    document.addEventListener("submit", async (e) => {

        e.preventDefault();
        if(e.target.dataset.action == "userLogin") {
            let form_data = loginController.formData(e.currentTarget);
            let newLogin = new Login(form_data);

            let result = await loginModel.put(newLogin);
            
            if(result.status.includes("OK")) {
                window.location = "home";
            }
        }

        if(e.target.dataset.action == "userSignup") {
            let form_data = loginController.formData(e.currentTarget);
            let newUser = new User(form_data);

            let result = await loginModel.post(newUser);
            
            if(result.status.includes("OK")) {
                window.location = "product";
            }
        }
    })
})();