<template>
  <div class="register-login-section">
    <div class="container">

      <div class="w-lg mx-auto sm:w-6/12 w-11/12 p-3 shadow-md">
        <div class=" px-4 pb-4 sm:p-6 sm:pb-4">
          <div class="">

            <div class="mt-3 text-center">
              <i class="fa fa-check-circle text-green-500 mb-3" style="font-size: 82px"></i>
              <h3 id="modal-headline" class="text-xl md:text-3xl font-medium text-gray-900">
                {{$page.$t.order.payment_confirmed}}
              </h3>
              <div class="mt-2">
                <p class="text-lg text-center">
                  {{$page.$t.order.payment_confirmed_message}}
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="flex justify-between">
          <button class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                  type="button">
            {{$page.$t.common.back_to_home}}
          </button>
        </div>
      </div>


    </div>
  </div>
</template>

<script>

export default {

};
</script>

<style>
</style>
