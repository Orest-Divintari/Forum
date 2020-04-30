<template>
  <div class="alert alert-success alert-flash" role="alert" v-show="show">
    <strong>Success!</strong>
    {{ body }}
    <button
      @click="close"
      type="button"
      class="close"
      data-dismiss="alert"
      aria-label="Close"
    >
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
</template>

<script>
export default {
  props: ["message"],
  data() {
    return {
      body: "",
      show: false
    };
  },

  created() {
    if (this.message) {
      this.flash(this.message);
    }

    window.events.$on("flash", message => {
      this.flash(message);
    });
  },

  methods: {
    flash(message) {
      this.body = message;
      this.show = true;
      this.hide();
    },

    close() {
      this.body = "";
      this.show = false;
    },

    hide() {
      setTimeout(() => {
        this.show = false;
      }, 3000);
    }
  }
};
</script>

<style>
.alert-flash {
  position: fixed;
  right: 25px;
  bottom: 2px;
}
</style>
