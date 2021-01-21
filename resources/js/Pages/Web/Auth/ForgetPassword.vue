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
                      class="p-2 text-red-500"
                      v-if="$page.errors.phone_number"
                    >
                      {{ $page.errors.phone_number }}
                    </div>
                  </div>
                 
                </div>

                <!-- <div class="group-input gi-check">
                  <div class="gi-more">
                    <a href="/web/forget_password" class="forget-pass"
                      >{{ $page.$t.profile.forget_password}}</a
                    >
                  </div>
                </div> -->
              </form>

              <button
                  type="button"
                  @click="submitForm"
                  class="site-btn login-btn"
                >
                {{ $page.$t.profile.forget_password}}
                </button>


              <div class="switch-login">
                <a href="/web/sign_in" class="or-login">{{ $page.$t.profile.login}}</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </web-layout>
</template>

<script>
import WebLayout from "../../../Layouts/WebAppLayout";

import VuePhoneNumberInput from "vue-phone-number-input";
import "vue-phone-number-input/dist/vue-phone-number-input.css";
export default {
  name: "Index",
  components: { VuePhoneNumberInput, WebLayout },
  data() {
    return {
      phone_number: "",
      password: "",
      first_name: "",
      last_name: "",
    };
  },

  methods: {
    submitForm() {
      this.$inertia.post("/web/forget_password", {
        phone_number: this.phone_number,
        password: this.password,
      });
    },
  },
};
</script>

<style scoped>
</style>