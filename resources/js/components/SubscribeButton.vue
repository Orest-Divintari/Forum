<template>
  <div>
    <button v-show="!isSubscribed" class="btn btn-dark" @click="subscribe">Subscribe</button>
    <button v-show="isSubscribed" class="btn btn-warning" @click="unsubscribe">Unsubscribe</button>
  </div>
</template>

<script>
export default {
  props: ["subscribed"],
  data() {
    return {
      isSubscribed: this.subscribed ? true : false
    };
  },
  methods: {
    endpoint() {
      var route = this.isSubscribed ? "unsubscribe" : "subscribe";
      return location.pathname + "/" + route;
    },
    subscribe() {
      axios
        .post(this.endpoint())
        .then(response => {
          this.isSubscribed = true;
          flash("isSubscribed!");
        })
        .catch(error => console.log(error.response.data.errros));
    },
    unsubscribe() {
      axios
        .post(this.endpoint())
        .then(response => {
          this.isSubscribed = false;
          flash("UnisSubscribed!");
        })
        .catch(error => console.log(error.response.data.errros));
    }
  }
};
</script>

<style lang="scss" scoped>
</style>