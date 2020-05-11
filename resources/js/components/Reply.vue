<template>
  <div :id="'reply-' + reply.id" class="card mt-3">
    <div class="card-header" :class="{'bg-success': isBest}">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <a :href="profile" v-text="reply.creator.name"></a> said
          <span v-text="ago"></span>...
        </div>

        <div class="d-flex">
          <div v-show="authorize('owns', thread)">
            <button v-show="!isBest" @click="markBestReply" class="btn btn-default">Best Reply</button>
          </div>
          <favorite-button v-show="signedIn" :current_reply="reply"></favorite-button>
          <div v-show="authorize('owns', reply)">
            <div class="d-flex">
              <button @click="destroy" class="btn btn-danger">Delete</button>
              <button type="button" v-if="!editing" class="ml-2 btn btn-light" @click="edit">Edit</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div>
      <div class="form-group" v-if="editing">
        <form @submit.prevent="update">
          <textarea
            name="body"
            style="resize:none"
            class="border-0 form-control"
            v-model="reply.body"
            required
          ></textarea>
          <button type="submit" v-if="editing" class="ml-2 btn btn-primary btn-sm">Update</button>
          <button v-show="editing" class="btn btn-link btn-sm" @click="editing = false">Cancel</button>
        </form>
      </div>
      <div v-html="reply.body" class="card-body" v-else></div>
      <div></div>
    </div>
  </div>
</template>
    
</template>
<script>
import Favorite from "./Favorite";
import moment from "moment";
export default {
  components: {
    "favorite-button": Favorite
  },
  props: ["reply"],
  data() {
    return {
      editing: false,
      thread: window.thread
    };
  },
  computed: {
    isBest() {
      return this.reply.id == thread.best_reply_id;
    },
    ago() {
      return moment(this.reply.created_at).fromNow();
    },
    profile() {
      return "/profiles/" + this.reply.creator.name;
    }
  },
  methods: {
    markBestReply() {
      axios.post("/replies/" + this.reply.id + "/best_reply");
      this.thread.best_reply_id = this.reply.id;
    },

    edit() {
      this.editing = true;
    },
    update() {
      axios
        .put("/replies/" + this.reply.id, { body: this.reply.body })
        .then(flash("Updated!"))
        .catch(error => flash(error.response.data, "danger"));
      this.editing = false;
    },
    onSuccess() {
      this.$emit("delete");
    },
    destroy() {
      axios
        .delete("/replies/" + this.reply.id)
        .then(this.onSuccess())
        .catch(error => console.log(error.response.data.errors));
    }
  },
  created() {}
};
</script>

<style lang="scss" scoped>
</style>