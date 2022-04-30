import { Controller, PartialView, EventData } from '../modules/controller.js';
import { User } from '../_model/user.js';

const users = new Controller("users", User);

(async () => {
    await users.view.downloadTemplate();
    //await users.model.importData();
    //let usersList = users.model.list;

    //Object.assign(userList, window.app);
    users.view.render({});
})();