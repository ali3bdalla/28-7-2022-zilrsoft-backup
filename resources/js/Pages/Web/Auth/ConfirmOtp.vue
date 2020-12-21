<template>
  <web-layout>
    <div class="register-login-section">
      <div class="container">
        <div class="login-form">
          <h2 class="text-xl">
            Confirm You Phone:
            <span class="text-web-primary">({{ hiddenPhoneNumber }})</span>
          </h2>
          <form action="#">
            <div class="flex flex-col w-1/2 mx-auto overflow-hidden">
              <div class="flex-1 group-input">
                <VueOTPField
                  :onFieldCompleted="onFieldCompleted"
                  :onFill="onFill"
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

export default {
  props: ["phone_number", "validate_url"],
  components: { VueOTPField, WebLayout },
  data() {
    return {
      otp: "",

    };
  },
  computed:{
    hiddenPhoneNumber()
    {
      return this.phone_number; //.replaceAt(-2, "********")

    }
  },
  methods: {
    onFieldCompleted(value) {
      this.$inertia.post(this.validate_url, {
        otp: value,
        phone_number: this.phone_number,
      });
    },
    onFill(inputObj) {

    },
  },
};
</script>

<style>
</style>>