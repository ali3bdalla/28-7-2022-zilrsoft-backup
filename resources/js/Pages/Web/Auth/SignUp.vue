<template>
  <web-layout>
    <div class="register-login-section">
      <div class="container">
        <div class="">
          <div class="col-lg-6 offset-lg-3">
            <div class="login-form">
              <h2>SignUp</h2>
              <form action="#">
                <div class="flex flex-col">
                  <div class="flex-1 group-input">
                    <label for="phone_number">Phone Number</label>
                    <VuePhoneNumberInput
                        v-model="phone_number"
                        :no-country-selector="false"
                        :no-example="true"
                        :only-countries="['SA']"
                        :translations="{
                        countrySelectorLabel: 'Country',
                        countrySelectorError: 'Choisir un pays',
                        phoneNumberLabel: '5555555555',
                        example: 'ex: 0500000000',
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
                    <label for="password">Password</label>
                    <input
                        id="password"
                        v-model="password"
                        auto-complete="new-zilrsoft-password"
                        autocomplete="new-zilrsoft-password"
                        placeholder="Password"
                        type="password"
                    />
                    <div v-if="$page.errors.password" class="p-2 text-red-500">
                      {{ $page.errors.password }}
                    </div>
                  </div>
                </div>

                <div class="flex">
                  <div class="flex-1 group-input">
                    <label for="first_name">First Name</label>
                    <input
                        id="first_name"
                        v-model="first_name"
                        placeholder="First Name"
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
                    <label for="last_name">Last Name</label>
                    <input
                        id="last_name"
                        v-model="last_name"
                        placeholder="Last Name"
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
                  Sign Up
                </button>
              </form>
              <div class="switch-login">
                <a class="or-login" href="/web/sign_in"
                >Already have account ?</a
                >
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

  components: {VuePhoneNumberInput, WebLayout},
  data() {
    return {

      phone_number: "",
      password: "",
      first_name: "",
      last_name: "",
    };
  },

  methods: {
    submitSignUp() {
      this.$inertia.post(
          "/web/sign_up",
          {
            phone_number: this.phone_number,
            password: this.password,
            first_name: this.first_name,
            last_name: this.last_name,
          }
      );
    },
  },

  computed: {},
  watch: {}
};
</script>

<style scoped>
</style>