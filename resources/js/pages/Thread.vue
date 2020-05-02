<script>
import Replies from "../components/Replies";
import SubscribeButton from "../components/SubscribeButton";
export default {
  props: ["replies_count"],

  components: {
    "subscribe-button": SubscribeButton,
    Replies
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
