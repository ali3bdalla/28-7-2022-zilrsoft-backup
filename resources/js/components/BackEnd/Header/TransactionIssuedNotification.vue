<template>
  <div>

    <h1 class="notification__dropdown-header ">
      <div class="notification__dropdown-title">تحويلات</div>
      <div class="notification__dropdown-icon">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path
              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
              stroke-linecap="round" stroke-linejoin="round"
              stroke-width="2"/>
        </svg>
      </div>
    </h1>
    <div v-for="(notification,index) in notifications" :key="index" class="">
      <div class="notification__dropdown-item">
        <div class="notification__dropdown-item__content">
          <div class="notification__dropdown-item__message">
            قام <span class="notification__dropdown-item__link">{{ notification.creator.ar_name }}</span> بتحويل
            مبلغ <span class="notification__dropdown-item__link">{{ notification.amount }}</span>
            من

            <span v-if="notification.from_account !== undefined && notification.from_account !== null"
                  class="notification__dropdown-item__link">{{
                notification.from_account.locale_name
              }}</span>
            الي

            <span
                v-if="notification.to_account !== undefined && notification.to_account !== null"
                class="notification__dropdown-item__link">{{
                notification.to_account.locale_name
              }}</span>
            <span class="notification__created_at">{{ notification.created_at }}</span>
          </div>
          <!--          <div class="notification__dropdown-item__created_at">-->
          <!--            {{ notification.created_at }}-->
          <!--          </div>-->
          <div class="notification__buttons">
            <a :href="`/daily/reseller/accounts_transactions/${notification.id}/confirm`">تاكيد الاستلام</a>
            <a :href="`/daily/reseller/accounts_transactions/${notification.id}/delete_transaction`">الغاء العملية</a>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import NotificationMixin from './NotificationMixin'

export default {
  mixins: [NotificationMixin],
  name: 'TransactionIssuedNotification',
  data () {
    return {
      url: '/api/notifications/transactions/issued'
    }
  },
  props: {
    manager: {
      type: Object,
      required: true
    }
  }

}
</script>
