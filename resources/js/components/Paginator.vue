<template>
  <ul v-show="shouldPaginate" class="pagination">
    <li v-if="prevUrl" class="page-item">
      <a @click.prevent="page--" name="prev" class="page-link" href="#" aria-label="Previous">
        <span aria-hidden="true">&laquo; Previous</span>
        <span class="sr-only">Previous</span>
      </a>
    </li>
    <li v-if="nextUrl" class="page-item">
      <a @click.prevent="page++" name="next" class="page-link" href="#" aria-label="Next">
        <span aria-hidden="true">Next &raquo;</span>
        <span class="sr-only">Next</span>
      </a>
    </li>
  </ul>
</template>

<script>
export default {
  props: ["dataset"],
  data() {
    return {
      page: 1,
      prevUrl: false,
      nextUrl: false
    };
  },
  methods: {
    broadcast() {
      this.$emit("changePage", this.page);
      return this;
    },
    updateUrl() {
      console.log();
      history.pushState(null, null, "?page=" + this.page);
    }
  },
  watch: {
    dataset() {
      this.page = this.dataset.current_page;
      this.prevUrl = this.dataset.prev_page_url;
      this.nextUrl = this.dataset.next_page_url;
    },
    page() {
      this.broadcast().updateUrl();
    }
  },
  computed: {
    shouldPaginate() {
      // paginate if we have a next or previous URL
      return this.prevUrl || this.nextUrl ? true : false;
    }
  },
  created() {},
  mounted() {}
};
</script>

<style lang="scss" scoped></style>
