<template>
  <web-layout>
    <div class="register-login-section">
      <div class="container">
        <div class="">
          <div class="col-lg-6 offset-lg-3">
            <div class="text-center login-form">
              <h2>{{ $page.$t.profile.sign_up}}</h2>
              <form action="#">
                <div class="flex flex-col">
                  <div class="flex-1 group-input  page__dir-left">
                    <label for="phone_number">{{ $page.$t.profile.phone_number}}</label>
                    <VuePhoneNumberInput
                        v-model="phone_number"
                        :no-country-selector="false"
                        :no-example="true"
                        :only-countries="['SA']"
                        :translations="{
                        countrySelectorLabel: $page.$t.profile.country,
                        countrySelectorError: 'Choisir un pays',
                         phoneNumberLabel: '5XXXXXXXXX',
                        example: 'ex: 5XXXXXXXXX',
                      }"
                        default-country-code="SA"
                    />

                    <div
                        v-if="$page.errors.phone_number"
                        class="p-2 text-red-500"
                    >
                      {{ $page.errors.phone_number }}
                    </div>
                  </div>
                  <div class="flex-1 group-input">
                    <label for="password">{{ $page.$t.profile.password}}</label>
                    <input
                        id="password"
                        v-model="password"
                        auto-complete="new-zilrsoft-password"
                        autocomplete="new-zilrsoft-password"

                        type="password"
                    />
                    <div v-if="$page.errors.password" class="p-2 text-red-500">
                      {{ $page.errors.password }}
                    </div>
                  </div>
                </div>

                <div class="flex">
                  <div class="flex-1 group-input">
                    <label for="first_name">{{ $page.$t.profile.first_name}}</label>
                    <input
                        id="first_name"
                        v-model="first_name"

                        type="text"
                    />
                    <div
                        v-if="$page.errors.first_name"
                        class="p-2 text-red-500"
                    >
                      {{ $page.errors.first_name }}
                    </div>
                  </div>
                  <div class="flex-1 group-input">
                    <label for="last_name">{{ $page.$t.profile.last_name}}</label>
                    <input
                        id="last_name"
                        v-model="last_name"

                        type="text"
                    />
                    <div v-if="$page.errors.last_name" class="p-2 text-red-500">
                      {{ $page.errors.last_name }}
                    </div>
                  </div>
                </div>
                <button
                    class="site-btn login-btn"
                    type="button"
                    @click="submitSignUp"
                >
                  {{ $page.$t.profile.sign_up}}
                </button>
              </form>
              <!-- <div class="switch-login">
                <a class="or-login" href="/web/sign_in"
                >{{ $page.$t.profile.already_have_account}}</a
                >
              </div> -->
            </div>
          </div>
        </div>

      </div>
    </div>
  </web-layout>
</template>

<script>
import WebLayout from '../../../Layouts/WebAppLayout'
import VuePhoneNumberInput from 'vue-phone-number-input'
import 'vue-phone-number-input/dist/vue-phone-number-input.css'

export default {
  name: 'Index',

  components: { VuePhoneNumberInput, WebLayout },
  data () {
    return {

      phone_number: '',
      password: '',
      first_name: '',
      last_name: ''
    }
  },

  methods: {
    submitSignUp () {
      this.$inertia.post(
        '/web/sign_up',
        {
          phone_number: this.phone_number,
          password: this.password,
          first_name: this.first_name,
          last_name: this.last_name
        }
      )
    }
  },

  computed: {},
  watch: {}
}
</script>
