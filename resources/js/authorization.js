let user = window.App.user;
let authorization = {
    owns(model) {
        return model.user_id == user.id;
    }
};
export default authorization;
