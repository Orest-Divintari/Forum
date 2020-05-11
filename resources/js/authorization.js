let user = window.App.user;
let authorization = {
    updateReply(reply) {
        return reply.user_id == user.id;
    },
    markBestReply(reply) {
        return user.id == window.thread.user_id;
    }
};
export default authorization;
