<template>
  <div>
    <input :value="value" :name="name" id="trix" type="hidden" />
    <trix-editor :value="inputValue" ref="trix" input="trix" :placeholder="placeholder"></trix-editor>
  </div>
</template>

<script>
import Trix from "trix";
export default {
  props: ["name", "value", "placeholder", "shouldClear"],
  data() {
    return {
      inputValue: this.value
    };
  },
  mounted() {
    console.log("handling");
    this.$refs.trix.addEventListener("trix-change", e => {
      this.$emit("input", e.target.innerHTML);
    });
  },
  watch: {
    shouldClear() {
      this.$refs.trix.value = "";
    }
  }
};
</script>

<style lang="scss" scoped>
</style>