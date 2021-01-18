<template>
  <div>

    <h1 class="notification__dropdown-header ">
      <div class="notification__dropdown-title">تاكيد سداد</div>
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
            <div> العميل : <span class="notification__dropdown-item__link">{{ notification.user.name }}</span></div>
            <div> رقم الطلب : <span class="notification__dropdown-item__link">{{ notification.id }}</span></div>
            <div> المبلغ : <span class="notification__dropdown-item__link"><display-money
                :money="notification.draft_invoice.net"/> </span></div>
            <!--            بالرقم <span class="notification__dropdown-item__link">{{ notification.id }}</span>-->
            <!--            من <span class="notification__dropdown-item__link">{{ notification.payment_detail.id }}</span>-->
            <!--            <span-->
            <!--                class="notification__dropdown-item__link">{{ notification.to_account.locale_name }}</span>-->
            <!--            <span class="notification__created_at">{{ notification.created_at }}</span>-->
          </div>
          <!--          <div class="notification__dropdown-item__created_at">-->
          <!--            {{ notification.created_at }}-->
          <!--          </div>-->
          <div class="notification__buttons">
            <a :href="`/store/orders/${notification.id}/view-payment`">عرض السداد</a>
            <a :href="`/sales/${notification.draft_id}`">عرض الطلب</a>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import NotificationMixin from "./NotificationMixin";

export default {
  mixins: [NotificationMixin],
  name: "OrderPendingPaymentConfirmationNotification",
  mounted() {
    console.log('OrderPendingPaymentConfirmationNotification')
    window.Echo.private(`order-payment-updated`).listen('.order-payment-updated', (e) => {
      this.addNotification(e.transaction);
    });
  },

  data() {
    return {
      url:"/api/notifications/orders/pending"
    }
  },

}
</script>
