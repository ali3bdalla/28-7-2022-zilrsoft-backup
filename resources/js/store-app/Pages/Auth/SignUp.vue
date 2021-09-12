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
                    <InternationalPhoneNumberSelectorComponent
                        v-model="phone_number"></InternationalPhoneNumberSelectorComponent>
                    <div
                        v-if="$page.errors && $page.errors.phone_number"
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
                    <div v-if="$page.errors && $page.errors.password" class="p-2 text-red-500">
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
                        v-if="$page.errors && $page.errors.first_name"
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
                    <div v-if="$page.errors && $page.errors.last_name" class="p-2 text-red-500">
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
            </div>
          </div>
        </div>

      </div>
    </div>
  </web-layout>
</template>

<script>
import WebLayout from '../../Layouts/WebAppLayout'
import InternationalPhoneNumberSelectorComponent from '../../Components/Utility/InternationalPhoneNumberSelectorComponent'

export default {
  name: 'Index',

  components: {
    InternationalPhoneNumberSelectorComponent,
    WebLayout
  },
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
