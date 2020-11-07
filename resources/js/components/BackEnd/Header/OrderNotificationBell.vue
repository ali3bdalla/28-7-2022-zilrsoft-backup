<template>
  <li class="dropdown notifications-menu">
    <a aria-expanded="true" class="dropdown-toggle" data-toggle="dropdown" href="#">
      <i class="fa fa-bell" style="font-size: 19px;
margin-bottom: -10px;"></i>
      <span class="label label-danger">{{ orders.length }}</span>
    </a>
    <ul class="dropdown-menu">
      <li>
        <!-- inner menu: contains the actual data -->
        <ul class="menu" style="    background: #eee;    max-height: 500px;">

          <li v-for="(order,index) in orders" :key="index">
            <div class="">
              <div class="panel-body">
                <i class="fa fa-warning text-yellow"></i> {{ order.user.name}} ،
                <span class="text-primary" style="    font-weight: bold;">{{parseFloat(order.net).toFixed(2)}}</span>
              </div>
<!--              <div class="panel-footer">-->
<!--                <a-->
<!--                    :href="'/daily/reseller/accounts_transactions/'+transaction.id+'/confirm'"-->
<!--                    class="btn btn-custom-primary pull-left">موافق</a>-->
<!--                <a :href="'/accounting/reseller_daily/'+transaction.id+-->
<!--                                                         '/delete_transaction'"-->
<!--                   class="btn btn-custom-default">الغاء </a>-->
<!--              </div>-->
            </div>
          </li>

        </ul>
      </li>
      <!--                            <li class="footer"><a href="#">View all</a></li>-->
    </ul>
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
      orders: []
    }
  },
  created() {
    this.loadOrders();
    Echo.private(`order.issued`).listen('.order.issued', (e) => {
      this.orders.push(e.order);
    });

    Echo.private(`order.pending`).listen('.order.pending', (e) => {
      this.orders.push(e.order);
    });

  },
  methods: {
    loadOrders() {
      axios.get('/api/orders/notification/list').then(res => this.orders = res.data);
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