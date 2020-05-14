<script>
import Replies from "../components/Replies";
import SubscribebButton from "../components/SubscribeButton";
export default {
  props: ["thread"],
  components: {
    Replies,
    "subscribe-button": SubscribebButton
  },
  data() {
    return {
      repliesCounter: this.thread.replies_count,
      locked: this.thread.locked,
      editing: false,
      title: this.thread.title,
      body: this.thread.body,
      form: {
        title: this.thread.title,
        body: this.thread.body
      }
    };
  },
  methods: {
    destroy() {
      axios
        .delete(this.update_endpoint())
        .then(() => this.onDelete())
        .catch(error => console.log(error.response));
    },

    edit() {
      this.editing = true;
    },
    cancel() {
      this.resetForm();
      this.editing = false;
    },
    resetForm() {
      this.form = {
        title: this.thread.title,
        body: this.thread.body
      };
    },
    data() {
      return this.form;
    },
    url() {
      return this.thread.path;
    },
    update() {
      axios
        .patch(this.url(), this.data())
        .then(({ data }) => this.onUpdate(data))
        .catch(error => console.log(error.response));
    },
    onUpdate(path) {
      this.updateUrl(path);
      flash("Thread has been updated!");
      this.editing = false;
      this.title = this.form.title;
      this.body = this.form.body;
    },
    updateUrl(path) {
      history.replaceState(null, null, path);
    },

    onDelete() {
      window.location.href = `/threads?by=${this.thread.creator.name}`;
    },
    update_endpoint() {},
    lock_endpoint() {
      return "/locked-threads/" + this.thread.slug;
    },
    lock() {
      axios
        .post(this.lock_endpoint())
        .then(() => (this.locked = true))
        .catch(error => console.log(error.response));
    },
    unlock() {
      axios
        .delete(this.endpoint())
        .then(() => (this.locked = false))
        .catch(error => console.log(error.response));
    }
  },
  mounted() {}
};
</script>

<style lang="scss" scoped></style>
