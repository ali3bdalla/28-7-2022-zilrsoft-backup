<template>
  <web-layout>
    <div class="register-login-section">
      <div class="container">
        <div class="login-form">
          <h2 class="text-xl">
            {{ $page.$t.profile.confirm_otp }}
          </h2>
          <form action="#">
            <div class="flex flex-col w-full md:w-1/2 mx-auto overflow-hidden">
              <div class="flex-1 group-input" style="    direction: ltr !important;">

                <input
        class="text-center"
                        v-model="otp"
                        type="number"
                         pattern="\d*"
                    />
                    <button
                    class="site-btn login-btn"
                    type="button"
                    @click="onFieldCompleted"
                >
                  {{ $page.$t.common.ok}}
                </button>

                <div class="p-2 text-red-500" v-if="$page.errors.otp">
                  {{ $page.$t.messages.invalid_otp }}
                </div>

                <div class="mt-3">
                  <div
                    v-if="timerCount > 0"
                    class=""
                    style="
                      font-size: 7em;
                      color: #e8aa3c;
                      font-weight: bold;
                      text-align: center;
                    "
                  >
                    {{ timerCount }}
                  </div>

                  <button
                    v-else
                    @click="resendOtp"
                    type="button"
                    class="site-btn login-btn mt-3"
                  >
                    {{ $page.$t.common.resend_otp }}
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </web-layout>
</template>

<script>
import WebLayout from '../../Layouts/WebAppLayout.vue'

export default {
  props: ['phone_number', 'validate_url'],
  components: {
    WebLayout
  },
  data () {
    return {
      enableResendButton: false,
      otp: '',
      setupNumber: 1,
      timerCount: 60,
      startCountingAt: null,
      finishCountingAt: null
    }
  },
  computed: {
    hiddenPhoneNumber () {
      return this.phone_number
    }
  },
  created () {
    this.resetTime()
  },

  methods: {
    resetTime () {
      const inerval = setInterval(() => {
        if (this.timerCount === 0) {
          clearInterval(inerval)
        } else {
          this.timerCount--
        }
      }, 1000)
    },
    endCallBack: function () {
      this.enableResendButton = true
    },

    resendOtp (value) {
      this.enableResendButton = false
      this.resetTime()
      this.$inertia.post('/web/resend_otp', {
        phone_number: this.phone_number,
        onSuccess: () => {
          alert('done')
        }
      })

      this.$inertia.on('exception', (event) => {
        event.preventDefault()
        console.log('An unexpected error occurred during an Inertia visit.')
        console.log(event.detail.error)
      })
      this.$inertia.on('invalid', (event) => {
        event.preventDefault()
        this.setupNumber++
        this.timerCount = 60 * this.setupNumber
        this.resetTime()
      })
    },
    onFieldCompleted () {
      this.$inertia.post(this.validate_url, {
        otp: this.otp,
        status: false,
        phone_number: this.phone_number
      })
    },
    onFill (inputObj) {}
  }
}
</script>
