<template>
  <div class="contact-form">
    <div class="leave-comment">
      <!--                <p>Our staff will call back later and answer your questions.</p>-->
      <div action="#" class="comment-form">
        <div class="row">
          <div class="col-lg-12">
            <div class="">
              <input
                type="text"
                style="direction: ltr !important;text-align: left !important;"
                :placeholder="$page.props.$t.profile.phone_number"
                v-model="$page.props.user.phone_number"
              />
              <div class="p-2 text-red-500" v-if="errors.phone_number">
                {{ errors.phone_number }}
              </div>
            </div>
          </div>

          <div class="col-lg-12" v-if="optSent">
            <div class="">
              <input

                type="number"
                :placeholder="$page.props.$t.profile.confirm_otp"
                v-model="otp"
              />
              <div class="p-2 text-red-500" v-if="errors.otp">
                {{ errors.otp }}
              </div>
            </div>
          </div>
          <div class="col-lg-12">
            <button type="submit" class="site-btn" @click="saveData">
              {{ $page.props.$t.common.update }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ChangePhoneNumberComponent',
  data () {
    return {
      optSent: false,
      otp: '',
      errors: []
    }
  },
  methods: {
    saveData () {
      axios
        .post('/web/profile/update-phone-number', {
          otp: this.otp,
          phone_number: this.$page.props.user.phone_number
        })
        .then((res) => {
          if (!this.otp) {
            this.optSent = true
          } else {
            this.$fire({
              title: this.$page.props.$t.messages.success,
              text: this.$page.props.$t.messages.phone_number_has_been_changed,
              type: 'success',
              timer: 3000
            }).then((r) => {
              console.log(r.value)
            })
            this.optSent = false
          }
          this.errors = {}
        })
        .catch((err) => {
          if (err.response.data.errors.phone_number) {
            this.errors = {
              phone_number: err.response.data.errors.phone_number
            }
          }

          if (err.response.data.errors.otp) {
            this.errors = { otp: err.response.data.errors.otp }
          }
        })
    }
  }
}
</script>

<style >
.contact-form .leave-comment .comment-form input {
  margin-bottom: 5px !important;
}
</style>
