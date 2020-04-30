<script>
import Replies from "../components/Replies";
export default {
    props: ["replies_count"],
    components: {
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
