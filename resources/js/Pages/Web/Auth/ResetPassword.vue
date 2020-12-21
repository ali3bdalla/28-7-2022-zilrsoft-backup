<template>
  <web-layout>
    <div class="register-login-section spad">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 offset-lg-3">
            <div class="login-form">
              <h2>{{ $page.$t.profile.login }}</h2>

              <form action="#">
                <div class="flex flex-col">
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
                      :placeholder="$page.$t.profile.password"
                    />
                    <div class="p-2 text-red-500" v-if="$page.errors.password">
                      {{ $page.errors.password }}
                    </div>
                  </div>
                </div>

                <div class="flex-1 group-input">
                  <label for="password">{{
                    $page.$t.profile.password_confirmation
                  }}</label>
                  <input
                    autocomplete="new-zilrsoft-password"
                    auto-complete="new-zilrsoft-password"
                    type="password"
                    id="password_confirmation"
                    v-model="password_confirmation"
                    :placeholder="$page.$t.profile.password_confirmation"
                  />
                  <div
                    class="p-2 text-red-500"
                    v-if="$page.errors.password_confirmation"
                  >
                    {{ $page.errors.password_confirmation }}
                  </div>
                </div>
              </form>

              <button
                type="button"
                @click="submitForm"
                class="site-btn login-btn"
              >
                {{ $page.$t.profile.login }}
              </button>


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

      password: "",
      password_confirmation: "",

    };
  },

  methods: {
    submitForm() {
      this.$inertia.post("/web/forget_password/reset", {
        password_confirmation: this.password_confirmation,
        password: this.password,
      });
    },
  },
};
</script>

<style scoped>
</style>