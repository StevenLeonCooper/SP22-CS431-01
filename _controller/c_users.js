import { Model, View, Controller } from '../modules/controller.js';
import { User } from '../_model/user.js';

const users = new Controller("users", User);

(async () => {
    await users.setup();
    let usersList = users.model.list;

    Object.assign(userList, window.app);
    users.view.render(userList);
})();