<template>
  <div>
    <div class="d-flex align-items-center">
      <img class="pb-1" :src="avatar" width="50" height="50" alt="noImage" />
      <h1 class="pt-4 ml-1">
        {{user.name}}
        <span class="reputation">{{reputation}}</span>
      </h1>
    </div>
    <form v-if="canUpdate" class="form-group">
      <avatar-input class="form-control-file" @loaded="load"></avatar-input>
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
  computed: {
    reputation() {
      return this.user.reputation + " XP";
    }
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
      let data = new FormData();
      data.append("avatar", avatar);
      return data;
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
.reputation {
  font-size: 50%;
}
</style>