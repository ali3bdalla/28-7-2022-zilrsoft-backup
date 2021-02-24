<template>
  <li class="dropdown notifications-menu">
    <a aria-expanded="true" class="dropdown-toggle" data-toggle="dropdown" href="#">
      <i class="fa fa-bell" style="font-size: 19px;
margin-bottom: -10px;"></i>
      <span class="label label-danger">{{ notificationCount }}</span>
    </a>
    <div class="dropdown-menu notification__dropdown">
      <OrderPendingPaymentConfirmationNotification v-if="canManageManagers"  @addNotification="addNotification"
                                                   @removeNotification="removeNotification">

      </OrderPendingPaymentConfirmationNotification>
      <OrderPaymentConfirmedNotification v-else @addNotification="addNotification"
                                         @removeNotification="removeNotification">
      </OrderPaymentConfirmedNotification>
      <TransactionIssuedNotification @addNotification="addNotification"
                                     @removeNotification="removeNotification">
      </TransactionIssuedNotification>

      <!--      <TransactionCanceledNotification @addNotification="addNotification"-->
      <!--                                       @removeNotification="removeNotification"></TransactionCanceledNotification>-->
      <!--      <TransactionConfirmedNotification @addNotification="addNotification"-->
      <!--                                        @removeNotification="removeNotification"></TransactionConfirmedNotification>-->
    </div>
  </li>
</template>

<script>
import OrderPendingPaymentConfirmationNotification from './OrderPendingPaymentConfirmationNotification'
import OrderPaymentConfirmedNotification from './OrderPaymentConfirmedNotification'
import TransactionIssuedNotification from './TransactionIssuedNotification'
// import TransactionCanceledNotification from './TransactionCanceledNotification'
// import TransactionConfirmedNotification from './TransactionConfirmedNotification'

export default {
  name: 'OrderNotificationBell',
  components: {
    OrderPendingPaymentConfirmationNotification,
    OrderPaymentConfirmedNotification,
    TransactionIssuedNotification
    // TransactionCanceledNotification,
    // TransactionConfirmedNotification
  },
  props: {
    canManageManagers: {
      type: Number
    },
    manager: {
      type: Object,
      required: true
    }
  },
  data () {
    return {
      notificationCount: 0
    }
  },

  methods: {
    addNotification () {
      // this.$sound.play('order_issued_notification.mp3')
      this.notificationCount++
    },
    removeNotification () {
      // this.$sound.play('order_issued_notification.mp3')
      this.notificationCount--
    }

  }

}
</script>

<style scoped>

</style>
