<template>
  <div>
    <h1 v-text="user.name"></h1>
    <img :src="avatar" width="50" height="50" alt="noImage" />
    <form v-if="canUpdate">
      <avatar-input @loaded="load"></avatar-input>
    </form>
  </div>
</template>

<script>
import AvatarInput from "./AvatarInput";

export default {
  components: {
    "avatar-input": AvatarInput
  },
  props: ["user"],
  data() {
    return {
      avatar: this.user.avatar_path
    };
  },
  methods: {
    load(avatar) {
      this.avatar = avatar.src;
      this.save(avatar.file);
    },
    authorizationPolicy(user) {
      return user.id === this.user.id;
    },
    canUpdate() {
      return this.authorize(this.authorizationPolicy.bind(this));
    },
    endpoint() {
      return `/api/users/${this.user.name}/avatar`;
    },
    format(avatar) {
      return (data = new FormData().append("avatar", avatar));
    },
    save(avatar) {
      let data = this.format(avatar);
      axios
        .post(this.endpoint(), data)
        .then(() => flash("Avatar Uploaded!"))
        .catch(error => console.log(error.response.data));
    }
  }
};
</script>

<style lang="scss" scoped>
</style>