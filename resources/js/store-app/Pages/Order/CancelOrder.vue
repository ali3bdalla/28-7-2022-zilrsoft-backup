<template>
  <div class="register-login-section">
    <div class="container">

      <div class="w-lg mx-auto sm:w-6/12 w-11/12 p-3 shadow-md">
        <div class=" px-4 pb-4 sm:p-6 sm:pb-4">
          <div class="">

            <div class="mt-3 text-center">
              <h3 id="modal-headline" class="text-lg leading-6 font-medium text-gray-900" @click="cancelOrder">
                {{$page.props.$t.order.cancel}}
              </h3>
              <div class="mt-2">
                <p class="text-lg text-center">
                  {{$page.props.$t.messages.are_you_sure}}
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="flex justify-between">
          <button class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                  @click="cancelOrder"
                  type="button">
            {{$page.props.$t.order.cancel}}
          </button>
          <button class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                  type="button">
            {{$page.props.$t.common.back_to_home}}
          </button>
        </div>
      </div>

    </div>
  </div>
</template>

<script>

export default {

  methods: {

    cancelOrder () {
      this.$inertia.post('/api/web/orders/' + this.$page.props.order.id + '/cancel')
    }

  }
}
</script>

<style>
</style>
