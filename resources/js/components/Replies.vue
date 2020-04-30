<template>
  <div>
    <reply v-for="(reply, index) in items" :key="reply.id" :data="reply" @delete="remove(index)"></reply>
    <paginator @changePage="fetchData" :dataset="dataset"></paginator>
    <reply-form @newReply="add"></reply-form>
  </div>
</template>

<script>
import Reply from "./Reply";
import ReplyForm from "./ReplyForm";
import collection from "../mixins/collection";

export default {
  components: {
    Reply,
    ReplyForm
  },
  mixins: [collection],

  data() {
    return {
      dataset: [],
      items: []
    };
  },

  computed: {},

  methods: {
    url(page) {
      if (!page) {
        // if the user provides the page in the uri
        // i.e location.pathname + "?page=2"
        // then we search the given number with regx with the constraint
        // that the uri that was passed by the user, starts with a page followed by a number ?page=2
        // in that case GET the number using query[1]
        // otherwise default to 1

        let query = location.search.match(/page=(\d+)/);
        page = query ? query[1] : 1;
      }
      return location.pathname + "/replies?page=" + page;
    },

    fetchData(page) {
      axios
        .get(this.url(page))
        .then(response => this.refresh(response))
        .catch(error => console.log(error.response.data.errors));
    },
    refresh({ data }) {
      this.dataset = data;

      // array of replies
      this.items = data.data;

      window.scrollTo(0, 0);
    }
  },

  created() {
    this.fetchData();
  },
  mounted() {}
};
</script>

<style lang="scss" scoped></style>
