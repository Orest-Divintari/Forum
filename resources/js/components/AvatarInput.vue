<template>
  <div>
    <input type="file" name="avatar" accept="image/*" @change="onChange" />
  </div>
</template>

<script>
export default {
  methods: {
    onChange($e) {
      let file = $e.target.files[0];
      if (!$e.target.files.length) return;
      var reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = $e => {
        var src = $e.target.result;

        this.$emit("loaded", { src, file });
      };
    }
  }
};
</script>

<style lang="scss" scoped>
</style>