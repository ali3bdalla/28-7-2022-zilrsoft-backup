<template>
  <web-layout>
    <div class="register-login-section">
      <div class="container">
        <div class="login-form">
          <h2 class="text-xl">
            {{ $page.$t.profile.confirm_otp }}
            <!-- <span class="text-web-primary">({{ hiddenPhoneNumber }})</span> -->
          </h2>
          <form action="#">
            <div class="flex flex-col w-1/2 mx-auto overflow-hidden">
              <div class="flex-1 group-input">
                <!-- <VueOTPField
                  :onFieldCompleted="onFieldCompleted"
                  :onFill="onFill"
                /> -->

                <v-otp-input
                  ref="otpInput"
                  input-classes="otp-input"
                  separator="-"
                  :num-inputs="4"
                  :should-auto-focus="true"
                  :is-input-num="true"
                  @on-change="onFill"
                  @on-complete="onFieldCompleted"
                />

                <div class="p-2 text-red-500" v-if="$page.errors.otp">
                  {{ $page.errors.otp }}
                </div>
              </div>
            </div>

            <div class="flex-1">
              <!-- <button type="button" class="site-btn login-btn">
          Sign Up
        </button> -->
            </div>
          </form>
        </div>
      </div>
    </div>
  </web-layout>
</template>

<script>
import VueOTPField from "vue-otp-field";
import WebLayout from "../../../Layouts/WebAppLayout";
import OtpInput from "@bachdgvn/vue-otp-input";

export default {
  props: ["phone_number", "validate_url"],
  components: { VueOTPField, WebLayout ,"v-otp-input": OtpInput},
  data() {
    return {
      otp: "",
    };
  },
  computed: {
    hiddenPhoneNumber() {
      return this.phone_number; //.replaceAt(-2, "********")
    },
  },
  methods: {
    onFieldCompleted(value) {
      this.$inertia.post(this.validate_url, {
        otp: value,
        phone_number: this.phone_number,
      });
    },
    onFill(inputObj) {},
  },
};
</script>

<style>
</style>>