<template>
  <div>
    <button @click="toggle" :class="classes" class="mr-2">
      <span>{{ reply.favorites_count }}</span>

      <span class="fa fa-heart"></span>
    </button>
  </div>
</template>

<script>
export default {
  props: ["current_reply"],
  data() {
    return {
      reply: this.current_reply
    };
  },
  computed: {
    classes() {
      return ["btn", this.reply.isFavorited ? "btn-success" : "btn-default"];
    },
    endpoint() {
      return "/replies/" + this.reply.id + "/favorites";
    }
  },
  methods: {
    toggle() {
      this.reply.isFavorited ? this.unfavorite() : this.favorite();
    },
    favorite() {
      axios
        .post(this.endpoint)
        .then(() => {
          flash("Liked!");
          this.reply.favorites_count++;
          this.reply.isFavorited = true;
        })
        .catch(() => flash("Failed to like"));
    },
    unfavorite() {
      axios
        .delete(this.endpoint)
        .then(() => {
          flash("Unliked!");
          this.reply.favorites_count--;
          this.reply.isFavorited = false;
        })
        .catch(() => flash("Failed to unlike"));
    }
  },
  mounted() {}
};
</script>

<style lang="scss" scoped></style>
