<template>
  <div>
    <form v-if="signedIn" @submit.prevent="post" @keydown="clear($event.target.name)">
      <div class="form-group mt-4">
        <wysiwyg
          :shouldClear="posted"
          v-model="body"
          name="body"
          placeholder="Have something to say..?"
        ></wysiwyg>
      </div>
      <button type="submit" :disabled="any()" class="btn btn-primary">Post</button>
    </form>
    <p v-else>
      Please
      <a href="/login">sign in</a> to participate in this forum
    </p>
    <p v-show="hasError('body')" class="text-center text-danger mt-2">{{ get("body") }}</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      body: "",
      errors: {},
      posted: false
    };
  },
  computed: {
    endpoint() {
      return location.pathname + "/replies";
    }
  },
  methods: {
    clear(field) {
      if (field) {
        delete this.errors[field];
      }
      this.errors = {};
    },
    hasError(field) {
      return this.errors.hasOwnProperty(field);
    },
    get(field) {
      return this.errors[field] ? this.errors[field][0] : "";
    },
    any() {
      return Object.keys(this.errors).length > 0;
    },
    post() {
      axios
        .post(this.endpoint, { body: this.body })
        .then(({ data }) => this.onSuccess(data))
        .catch(error => {
          flash(error.response.data, "danger");
        });
    },
    onSuccess(data) {
      this.$emit("newReply", data);
      this.body = "";
      this.posted = true;
      flash("Reply has been published");
    }
  }
};
</script>

<style lang="scss" scoped></style>
