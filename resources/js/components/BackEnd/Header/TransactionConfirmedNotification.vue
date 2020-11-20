<template>
  <div class="notification__dropdown-item">
    <div class="notification__dropdown-item__icon">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path
            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
            stroke-linecap="round" stroke-linejoin="round"
            stroke-width="2"/>
      </svg>
    </div>
    <div class="notification__dropdown-item__content">
      <div class="notification__dropdown-item__message">
        <span class="notification__dropdown-item__link">{{ notification.user.name }}</span> Has Issued New order
        with
        number <span class="notification__dropdown-item__link">{{ notification.id }}</span>

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
</template>

<script>
export default {
  name: "TransactionConfirmedNotification",

}
</script>

<style scoped>

</style>