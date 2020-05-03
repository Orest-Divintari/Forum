<template>
  <div v-if="notifications.length" class="dropdown">
    <a href class="nav-link" data-toggle="dropdown">
      <span class="fa fa-bell"></span>
    </a>

    <div class="dropdown-menu">
      <a
        @click="markAsRead(notification.id, index)"
        v-for="(notification,index) in notifications"
        :key="notification.id"
        class="dropdown-item"
        v-text="notification.data.message"
        :href="notification.data.link"
      ></a>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      notifications: []
    };
  },
  methods: {
    endpoint() {
      return "/profiles/" + window.App.user.name + "/notifications";
    },

    markAsRead(notificationId, index) {
      axios
        .delete(this.endpoint() + "/" + notificationId)
        .then(this.notifications.splice(index, 1));
    }
  },
  created() {
    axios
      .get(this.endpoint())
      .then(response => (this.notifications = response.data));
  }
};
</script>

<style lang="scss" scoped>
</style>