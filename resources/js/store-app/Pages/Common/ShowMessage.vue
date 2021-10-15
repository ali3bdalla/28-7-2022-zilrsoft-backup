<template>
  <WebAppLayout>
    <div class="register-login-section">
      <div class="container">
        <div class="w-lg mx-auto sm:w-6/12 w-11/12 p-3 shadow-md " :class="{'bg-green-300':$page.props.messageType=='success'}">
          <div class="px-4 pb-4 sm:p-6 sm:pb-4">
            <div class="">
              <div class="mt-3 text-center">
                <!-- <h3
                  id="modal-headline"
                  class="text-lg leading-6 font-medium text-gray-900"
                >
                  {{ title }}
                </h3> -->
                <div class="mt-2">
                  <p class="text-lg text-center">
                    {{ message }}
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="flex justify-end">
            <a
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              href="/web"
            >
              {{ $page.props.$t.common.back_to_home }}
            </a>
          </div>
        </div>
      </div>
    </div>
  </WebAppLayout>
</template>

<script>
import WebAppLayout from '../../Layouts/WebAppLayout'

export default {
  components: { WebAppLayout },
  computed: {
    title () {
      if (this.$page.props.title) { return this.$page.props.title }

      return this.$page.props.$t.common.title_message
    },
    message () {
      if (this.$page.props.message) { return this.$page.props.message }

      return this.$page.props.$t.common.completed_message
    }
  }
}
</script>

<style>
</style>
