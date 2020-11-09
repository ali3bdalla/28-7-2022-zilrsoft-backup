<template>
  <li class="dropdown notifications-menu">
    <a aria-expanded="true" class="dropdown-toggle" data-toggle="dropdown" href="#">
      <i class="fa fa-bell" style="font-size: 19px;
margin-bottom: -10px;"></i>
      <span class="label label-danger">{{ notifications.length }}</span>
    </a>
    <div class="dropdown-menu notification__dropdown">
      <div v-for="(notification,index) in notifications " :key="index" class="">
       <div class="notification__dropdown-item" v-if="notification.notification_type === 'order'">
         <div class="notification__dropdown-item__icon">
           <svg  fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
             <path
                 d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                 stroke-linecap="round" stroke-linejoin="round"
                 stroke-width="2"/>
           </svg>

         </div>

         <div class="notification__dropdown-item__content">
           <div class="notification__dropdown-item__message">
             <span class="notification__dropdown-item__link">{{notification.user.name }}</span> Has Issued New order with
             number <span class="notification__dropdown-item__link">{{notification.id}}</span>

           </div>
           <div class="notification__dropdown-item__created_at">
             {{ notification.created_at }}
           </div>
            <div class="notification__dropdown-item__buttons">
                <a>Confim</a>
                <a>Update</a>
                <a>Get</a>
            </div>
         </div>

       </div>


<!--        <svg  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>-->

      </div>
    </div>
  </li>
</template>

<script>
export default {
  name: "OrderNotificationBell",
  props: {
    manager: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      notifications: []
    }
  },
  created() {
    this.loadOrders();
    Echo.private(`order.issued`).listen('.order.issued', (e) => {
      this.addOrderNotification(e.order);
    });

    Echo.private(`order.pending`).listen('.order.pending', (e) => {
      this.addOrderNotification(e.order);
    });

  },
  methods: {

    addOrderNotification(order)
    {
        let notification = order;
        notification.notification_type = 'order';
        this.notifications.push(notification);
    },
    loadOrders() {
      axios.get('/api/orders/notification/list').then(res => {
        res.data.forEach((order)=>{
          this.addOrderNotification(order);
        })
      });
    },
  },
  watch: {
    orders: function (newValue) {
      this.$sound.play('order_issued_notification.mp3');
    }
  }
}
</script>

<style scoped>

</style>