<template>
  <web-layout>
    <div class="register-login-section spad">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 offset-lg-3">
            <div class="login-form">
              <h2>Login</h2>

              <form action="#">
                <div class="flex flex-col">
                  <div class="flex-1 group-input">
                    <label for="phone_number">Phone Number</label>
                    <VuePhoneNumberInput
                      default-country-code="SA"
                      :no-example="true"
                      :only-countries="['SA']"
                      :translations="{
                        countrySelectorLabel: 'Country',
                        countrySelectorError: 'Choisir un pays',
                        phoneNumberLabel: '5555555555',
                        example: 'ex: 500000000',
                      }"
                      :no-country-selector="false"
                      v-model="phone_number"
                    />

                    <div
                      class="p-2 text-red-500"
                      v-if="$page.errors.phone_number"
                    >
                      {{ $page.errors.phone_number }}
                    </div>
                  </div>
                  <div class="flex-1 group-input">
                    <label for="password">Password</label>
                    <input
                      autocomplete="new-zilrsoft-password"
                      auto-complete="new-zilrsoft-password"
                      type="password"
                      id="password"
                      v-model="password"
                      placeholder="Password"
                    />
                    <div class="p-2 text-red-500" v-if="$page.errors.password">
                      {{ $page.errors.password }}
                    </div>
                  </div>
                </div>

                <div class="group-input gi-check">
                  <div class="gi-more">
                    <a href="/web/forget_password" class="forget-pass"
                      >Forget your Password</a
                    >
                  </div>
                </div>
              </form>

              <button
                  type="button"
                  @click="submitForm"
                  class="site-btn login-btn"
                >
                  Sign In
                </button>


              <div class="switch-login">
                <a href="/web/sign_up" class="or-login">Or Create An Account</a>
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
      this.$inertia.post("/web/sign_in", {
        phone_number: this.phone_number,
        password: this.password,
      });
    },
  },
};
</script>

<style scoped>
</style>