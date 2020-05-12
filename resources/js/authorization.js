let user = window.App.user;
let authorization = {
    owns(model) {
        return model.user_id == user.id;
    },
    isAdmin() {
        return ["uric", "orestis", "orest"].includes(user.name);
    }
};
export default authorization;
