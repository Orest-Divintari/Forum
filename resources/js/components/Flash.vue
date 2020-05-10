<template>
  <div class="alert alert-flash" :class="'alert-' + level" role="alert" v-show="show" v-text="body">
    <button @click="close" type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
</template>

<script>
export default {
  props: ["message"],
  data() {
    return {
      body: this.message,
      show: false,
      level: "success"
    };
  },

  created() {
    if (this.message) {
      this.flash();
    }

    window.events.$on("flash", data => {
      this.flash(data);
    });
  },

  methods: {
    flash(data) {
      if (data) {
        this.body = data.message;
        this.level = data.level;
      }
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
