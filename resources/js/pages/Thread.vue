<script>
import Replies from "../components/Replies";
import SubscribebButton from "../components/SubscribeButton";
import Highlight from "../components/Highlight";
export default {
  props: ["thread"],
  components: {
    Replies,
    "subscribe-button": SubscribebButton,
    Highlight
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
        .delete(this.delete_enpoint())
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
      console.log("g");
      window.location.href = `/threads`;
    },
    delete_enpoint() {
      return `/threads/${this.thread.channel.id}/${this.thread.slug}`;
    },
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
        .delete(this.lock_endpoint())
        .then(() => (this.locked = false))
        .catch(error => console.log(error.response));
    }
  },
  mounted() {}
};
</script>

<style lang="scss" scoped></style>
