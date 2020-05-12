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
      locked: this.thread.locked
    };
  },
  methods: {
    endpoint() {
      return "/locked-threads/" + this.thread.slug;
    },
    lock() {
      axios
        .post(this.endpoint())
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
