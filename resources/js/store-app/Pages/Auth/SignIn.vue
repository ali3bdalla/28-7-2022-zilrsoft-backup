<template>
  <web-layout>
    <div class="register-login-section spad">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 offset-lg-3">
            <div class="text-center login-form" v-if="activePage == 'login'">
              <h2>{{ $page.$t.profile.login }}</h2>

              <form action="#">
                <div class="flex flex-col">
                  <div class="flex-1 group-input page__dir-left">
                    <label for="phone_number">{{
                      $page.$t.profile.phone_number
                    }}</label>
                    <VuePhoneNumberInput
                      default-country-code="SA"
                      :no-example="true"
                      :only-countries="['SA']"
                      :translations="{
                        countrySelectorLabel: $page.$t.profile.country,
                        phoneNumberLabel: '5XXXXXXXXX',
                        example: 'ex: 5XXXXXXXXX',
                      }"
                      :no-country-selector="false"
                      v-model="phone_number"
                    />

                    <div
                      class="p-2 text-red-500 mt-2"
                      v-if="$page.errors.phone_number"
                    >
                      {{ $page.errors.phone_number }}
                    </div>
                  </div>
                  <div class="flex-1 group-input">
                    <label for="password">{{
                      $page.$t.profile.password
                    }}</label>
                    <input
                      autocomplete="new-zilrsoft-password"
                      auto-complete="new-zilrsoft-password"
                      type="password"
                      id="password"
                      v-model="password"
                    />
                    <div
                      class="p-2 text-red-500 mt-2"
                      v-if="$page.errors.password"
                    >
                      {{ $page.errors.password }}
                    </div>
                  </div>
                </div>

                <div class="group-input gi-check flex items-center justify-between">
                  <div class="gi-more">
                    <a
                      href="/web/forget_password"
                      :style="$page.active_locale == 'en' ? 'float:left' : ''"
                      class="forget-pass"
                      >{{ $page.$t.profile.forget_password }}</a
                    >
                  </div>
                  <div class="gi-more">
                    <a
                     href="/web/sign_up"
                      :style="$page.active_locale == 'en' ? 'float:right' : ''"
                      class="forget-pass"
                      >{{ $page.$t.profile.or_create_new_account }}</a
                    >
                  </div>
                </div>
              </form>

              <button
                type="button"
                @click="submitForm"
                class="site-btn login-btn mt-3"
              >
                {{ $page.$t.profile.login }}
              </button>

              <!-- <div class="switch-login">
                <a href="/web/sign_up" class="or-login">{{ $page.$t.profile.or_create_new_account}}</a>
              </div> -->
            </div>
            <div class="text-center login-form" v-else>
              <div
                class="flex flex-col md:flex-row items-center justify-between gap-3 md:gap-5"
              >
                <button
                  @click="setLoginPage"
                  class="bg-white py-5 w-full  flex flex-col items-center gap-6 justify-between shadow-sm hover:text-gray-500"
                >
                  <i class="fa fa-user-circle  text-yellow-600  text-6xl"></i>
                  <span class="text-xl">{{
                    $page.$t.profile.exists_customer
                  }}</span>
                </button>

                <inertia-link
                  href="/web/sign_up"
                  class="bg-white py-5 w-full  flex flex-col items-center gap-6 justify-between shadow-sm hover:text-gray-500"
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
import VuePhoneNumberInput from 'vue-phone-number-input'
import 'vue-phone-number-input/dist/vue-phone-number-input.css'
export default {
  name: 'Index',
  components: { VuePhoneNumberInput, WebLayout },
  data () {
    return {
      activePage: null,
      phone_number: '',
      password: '',
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
