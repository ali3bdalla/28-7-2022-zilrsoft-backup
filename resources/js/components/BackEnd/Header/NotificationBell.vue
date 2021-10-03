<template>
  <li class="dropdown notifications-menu">
    <a aria-expanded="true" class="dropdown-toggle" data-toggle="dropdown" href="#">
      <i class="fa fa-bell notification_bell"></i>
      <span class="label label-danger">{{ unreadNotificationsCount }}</span>
    </a>
    <div class="dropdown-menu notification__dropdown">
        <div>
          <div v-for="(notification,index) in notificationsList" :key="index" class="">
            <div class="notification__dropdown-item">
              <div class="notification__dropdown-item__content">
                <div class="notification__dropdown-item__message">{{ notification.data.message }}</div>
              </div>
              <div class="notification__buttons">
                <a @click="markAsRead(notification)" v-for="(action,actionIndex) in notification.data.actions" :key="actionIndex" :href="action.action">{{ action.title }}</a>
              </div>
            </div>
          </div>
        </div>
      </div>
  </li>
</template>
<script>

export default {
  name: 'OrderNotificationBell',
  props: {
    canManageManagers: {
      type: Number
    },
    canViewSystemEvents: {
      type: Number
    },
    manager: {
      type: Object,
      required: true
    }
  },
  data () {
    return {
      unreadNotificationsCount: 0,
      notificationsList: []
    }
  },
  created(){
    this.fetchDatabaseNotifications();
  },
  mounted () {
    this.watchRealTimeNotifications();
  },
  methods: {
    fetchDatabaseNotifications() {
        axios.get('/api/notifications').then(res => {
          this.notificationsList = res.data.data;
          this.unreadNotificationsCount = this.notificationsList.length;
        })
    },
    watchRealTimeNotifications () {
      Echo.private('manager_notification_channel_.1')
          .notification((notification) => {
            this.addNotification({data: notification,id: notification.id,type:notification.type})
          })
    },
    addNotification (notification) {
      this.notificationsList.push(notification)
      console.log(notification)
      this.unreadNotificationsCount++
    },
    markAsRead (notification) {
      this.unreadNotificationsCount--
      axios.get('/api/notifications/' + notification.id + '/mark_as_read')
    },
  }

}
</script>

<style scoped>

.notification__dropdown-item {
  flex-direction: column !important;
}
.notification__dropdown-item__content {
 font-size: 16px;
}
.notification_bell {
  font-size: 19px;
  margin-bottom: -10px;
}
</style>
