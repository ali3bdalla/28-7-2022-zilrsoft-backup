<template>
  <li class="dropdown notifications-menu">
    <a aria-expanded="true" class="dropdown-toggle" data-toggle="dropdown" href="#">
      <i class="fa fa-bell" style="font-size: 19px;
margin-bottom: -10px;"></i>
      <span class="label label-danger">{{ notificationCount }}</span>
    </a>
    <div class="dropdown-menu notification__dropdown">
      <OrderPendingPaymentConfirmationNotification v-if="canManageManagers || canViewSystemEvents"
                                                   @addNotification="addNotification"
                                                   @removeNotification="removeNotification">
      </OrderPendingPaymentConfirmationNotification>
      <OrderPaymentConfirmedNotification v-if="!canManageManagers" @addNotification="addNotification"
                                         @removeNotification="removeNotification">
      </OrderPaymentConfirmedNotification>
      <TransactionIssuedNotification
                                  :manager="manager"
                                     @addNotification="addNotification"
                                     @removeNotification="removeNotification">
      </TransactionIssuedNotification>
    </div>
  </li>
</template>

<script>
import OrderPendingPaymentConfirmationNotification from './OrderPendingPaymentConfirmationNotification'
import OrderPaymentConfirmedNotification from './OrderPaymentConfirmedNotification'
import TransactionIssuedNotification from './TransactionIssuedNotification'

export default {
  name: 'OrderNotificationBell',
  components: {
    OrderPendingPaymentConfirmationNotification,
    OrderPaymentConfirmedNotification,
    TransactionIssuedNotification
  },
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
      notificationCount: 0
    }
  },

  methods: {
    addNotification () {
      this.notificationCount++
    },
    removeNotification () {
      this.notificationCount--
    }

  }

}
</script>

<style scoped>

</style>
