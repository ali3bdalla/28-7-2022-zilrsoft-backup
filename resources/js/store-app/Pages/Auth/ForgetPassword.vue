<template>
  <web-layout>
    <div class="register-login-section spad">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 offset-lg-3">
            <div class="text-center login-form">
              <h2>{{ $page.$t.profile.forget_password}}</h2>

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

                </div>
              </form>

              <button
                  class="site-btn login-btn"
                  type="button"
                  @click="submitForm"
              >
                {{ $page.$t.profile.forget_password}}
              </button>

              <div class="switch-login">
                <a class="or-login" href="/web/sign_in">{{ $page.$t.profile.login}}</a>
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
  name: 'ForgetPassword',
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
    submitForm () {
      this.$inertia.post('/web/forget_password', {
        phone_number: this.phone_number,
        password: this.password
      })
    }
  }
}
</script>

<style scoped>
</style>
