<template>
  <div>
    <button :class="classes" @click="subscribe">Subscribe</button>
  </div>
</template>

<script>
export default {
  props: ["active"],
  data() {
    return {
      isSubscribed: this.active
    };
  },
  computed: {
    classes() {
      return ["btn", this.isSubscribed ? "btn-primary" : "btn-link"];
    },
    requestType() {
      return this.isSubscribed ? "delete" : "post";
    }
  },
  methods: {
    endpoint() {
      return location.pathname + "/subscriptions";
    },
    subscribe() {
      axios[this.requestType](this.endpoint());
      this.isSubscribed = !this.isSubscribed;
    },
    unsubscribe() {
      axios.delete(this.endpoint()).then(flash("Unsubscribed!"));
    }
  }
};
</script>

<style lang="scss" scoped>
</style>