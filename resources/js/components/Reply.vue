<template>
    <div name="kitsos" :id="'reply-' + reply.id" class="card mt-3">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a :href="profile" v-text="reply.creator.name">
                        
                    </a > said </span><span v-text="ago"> </span>...
                </div>


                <div class="d-flex">
                    <favorite-button v-show="signedIn" :current_reply="reply"></favorite-button>
                    
                    <div v-show="canUpdate">
                        <button @click="destroy" class="btn btn-danger">Delete</button>
                        <button v-if="editing" class="ml-2 btn btn-primary" @click="update">Update</button>
                        <button v-else="editing" class="ml-2 btn btn-light" @click="edit">Edit</button>
                    </div>
                </div>
                

            </div>
        </div>

        <div>
            <div class="form-group" v-if="editing">
                <textarea style="resize:none" class=" border-0 form-control" v-model="reply.body"></textarea>
            </div>
            <div class="card-body" v-else>
                {{reply.body}}
            </div>
            <div>
                <button v-show="editing" class="btn btn-link" @click="editing = false">Cancel</button>

            </div>
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
  props: ["data"],
  computed: {
    ago()
    {
      return moment(this.created_at).fromNow();
    },
      canUpdate()
      {
          
          return this.authorize(this.authorizationMethod.bind(this));
      },
      profile()
      {
          return "/profiles/" + this.reply.creator.name;
      },
      signedIn() {
          return window.App.signedIn;
      },
     

  },
  data() {
    return {
      reply: {},
      editing: false
    };
  },
  methods: {
      authorizationMethod(user){
          return user.id == this.reply.creator.id
      },
    edit() {
      this.editing = true;
    },
    update() {
      axios
        .put("/replies/" + this.reply.id, { body: this.reply.body })
        .then(flash("Updated!"))
        .catch(flash("Failed To Update"));
      this.editing = false;
    },
    onSuccess() {

        this.$emit('delete');
        // this.$el.parentNode.removeChild(this.$el);
    },
    destroy() {
      axios
        .delete("/replies/" + this.reply.id)
        .then(this.onSuccess())
        .catch(error => console.log(error.response.data.errors));
    }
  },
  created() {
    this.reply = this.data;
  }
};
</script>

<style lang="scss" scoped>
</style>