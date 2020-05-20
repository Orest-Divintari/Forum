<template>
  <div v-html="content" ref="hlContent"></div>
</template>

<script>
import hljs from "highlight.js";
import "highlight.js/styles/github.css";
export default {
  props: ["content"],
  methods: {
    highlight(block) {
      if (!block) {
        return;
      }
      block.querySelectorAll("pre").forEach(node => {
        hljs.highlightBlock(node);
      });
    }
  },
  mounted() {
    this.highlight(this.$refs.hlContent);
  }
};
</script>

<style lang="scss" scoped>
</style>