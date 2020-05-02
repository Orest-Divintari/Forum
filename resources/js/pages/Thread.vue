<script>
import Replies from "../components/Replies";
import SubscribebButton from "../components/SubscribeButton";
export default {
  props: ["replies_count"],
  components: {
    Replies,
    "subscribe-button": SubscribebButton
  },
  data() {
    return {
      repliesCounter: this.replies_count
    };
  },
  methods: {}
};
</script>

<style lang="scss" scoped></style>
