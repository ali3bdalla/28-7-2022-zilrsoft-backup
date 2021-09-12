<template>
  <web-layout>
    <div class="register-login-section spad">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 offset-lg-3">
            <div v-if="activePage === 'login'" class="text-center login-form">
              <h2>{{ $page.$t.profile.login }}</h2>

              <form action="#">
                <div class="flex flex-col">
                  <div class="flex-1 group-input page__dir-left">
                    <label for="phone_number">{{
                        $page.$t.profile.phone_number
                      }}</label>
                    <InternationalPhoneNumberSelectorComponent
                        v-model="phone_number"></InternationalPhoneNumberSelectorComponent>
                    <div
                        v-if="$page.errors && $page.errors.phone_number"
                        class="p-2 text-red-500 mt-2"
                    >
                      {{ $page.errors.phone_number }}
                    </div>
                  </div>
                  <div class="flex-1 group-input">
                    <label for="password">{{
                        $page.$t.profile.password
                      }}</label>
                    <input
                        id="password"
                        v-model="password"
                        auto-complete="new-zilrsoft-password"
                        autocomplete="new-zilrsoft-password"
                        type="password"
                    />
                    <div
                        v-if="$page.errors && $page.errors.password"
                        class="p-2 text-red-500 mt-2"
                    >
                      {{ $page.errors.password }}
                    </div>
                  </div>
                </div>

                <div class="group-input gi-check flex items-center justify-between">
                  <div class="gi-more">
                    <a
                        :style="$page.active_locale === 'en' ? 'float:left' : ''"
                        class="forget-pass"
                        href="/web/forget_password"
                    >{{ $page.$t.profile.forget_password }}</a
                    >
                  </div>
                  <div class="gi-more">
                    <a
                        :style="$page.active_locale == 'en' ? 'float:right' : ''"
                        class="forget-pass"
                        href="/web/sign_up"
                    >{{ $page.$t.profile.or_create_new_account }}</a
                    >
                  </div>
                </div>
              </form>

              <button
                  class="site-btn login-btn mt-3"
                  type="button"
                  @click="submitForm"
              >
                {{ $page.$t.profile.login }}
              </button>
            </div>
            <div v-else class="text-center login-form">
              <div
                  class="flex flex-col md:flex-row items-center justify-between gap-3 md:gap-5"
              >
                <button
                    class="bg-white py-5 w-full  flex flex-col items-center gap-6 justify-between shadow-sm hover:text-gray-500"
                    @click="setLoginPage"
                >
                  <i class="fa fa-user-circle  text-yellow-600  text-6xl"></i>
                  <span class="text-xl">{{
                      $page.$t.profile.exists_customer
                    }}</span>
                </button>

                <inertia-link
                    class="bg-white py-5 w-full  flex flex-col items-center gap-6 justify-between shadow-sm hover:text-gray-500"
                    href="/web/sign_up"
                >
                  <i class="fa fa-user-plus text-yellow-600  text-6xl"></i>
                  <span class="text-xl">{{
                      $page.$t.profile.new_customer
                    }}</span>
                </inertia-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </web-layout>
</template>

<script>
import WebLayout from '../../Layouts/WebAppLayout'

import InternationalPhoneNumberSelectorComponent
  from '../../Components/Utility/InternationalPhoneNumberSelectorComponent'

export default {
  name: 'Index',
  components: {
    InternationalPhoneNumberSelectorComponent,
    WebLayout
  },
  data () {
    return {
      activePage: null,
      phone_number: '',
      password: '966324018',
      first_name: '',
      last_name: ''
    }
  },

  methods: {
    setLoginPage () {
      this.activePage = 'login'
    },
    submitForm () {
      this.$inertia.post('/web/sign_in', {
        phone_number: this.phone_number,
        password: this.password
      })
    }
  }
}
</script>

<style scoped>
</style>
