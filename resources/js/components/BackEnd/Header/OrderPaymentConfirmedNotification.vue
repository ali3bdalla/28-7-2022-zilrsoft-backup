<template>
  <div>
    <h1 class="notification__dropdown-header ">
      <div class="notification__dropdown-title"> طلبات اونلاين</div>
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
            <div> يوجد طلب جديد : <span class="notification__dropdown-item__link">{{ notification.user.name }}</span>
            </div>
            <div> رقم الطلب : <span class="notification__dropdown-item__link">{{ notification.id }}</span></div>
            <!--            <div> المبلغ : <span class="notification__dropdown-item__link"><display-money-->
            <!--                :money="notification.draft_invoice.net"/> </span></div>-->
          </div>

          <div class="notification__buttons">
            <a :href="`/orders/${notification.id}/confirm`">قبول الطلب</a>
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
  name: "OrderPaymentConfirmedNotification",
  mounted() {
    window.Echo.private(`order-payment-confirmed`).listen('.order-payment-confirmed', (e) => {
      this.addNotification(e.transaction);
    });
  },
  data() {
    return {
      url:"/api/notifications/orders/paid"
    }
  },
}
</script>
