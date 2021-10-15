<template>
  <div class="contact-form">
    <div class="leave-comment">
      <!--                <p>Our staff will call back later and answer your questions.</p>-->
      <div action="#" class="comment-form">
        <div class="row">
          <div class="col-lg-12">
            <div class="">
              <input
                type="password"
                :placeholder="$page.props.$t.profile.password"
                v-model="old_password"
              />
              <div class="p-2 text-red-500" v-if="errors.old_password">
                {{ errors.old_password }}
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="">
              <input
                type="password"
                :placeholder="$page.props.$t.profile.new_password"
                v-model="password"
              />
              <div class="p-2 text-red-500" v-if="errors.password">
                {{ errors.password }}
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="">
              <input
                type="password"
                v-model="password_confirmation"
                :placeholder="$page.props.$t.profile.password_confirmation"
              />
              <div class="p-2 text-red-500" v-if="errors.password_confirmation">
                {{ errors.password_confirmation }}
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
  name: 'ChangePasswordComponent',
  data () {
    return {
      password: '',
      password_confirmation: '',
      old_password: '',
      errors: []
    }
  },
  methods: {
    saveData () {
      axios
        .post('/web/profile/update-password', this.$data)
        .then((res) => {
          this.$fire({
            title: this.$page.props.$t.messages.success,
            text: this.$page.props.$t.messages.password_has_been_changed,
            type: 'success',
            timer: 3000
          }).then((r) => {
            console.log(r.value)
          })
          this.old_password = ''
          this.password = ''
          this.password_confirmation = ''

          this.errors = {}
        })
        .catch((err) => {
          if (err.response.data.errors.password_confirmation) {
            this.errors = {
              password_confirmation:
                err.response.data.errors.password_confirmation
            }
          }

          if (err.response.data.errors.old_password) {
            this.errors = {
              old_password: err.response.data.errors.old_password
            }
          }

          if (err.response.data.errors.password) {
            this.errors = { password: err.response.data.errors.password }
          }

          // this.errors =
          // err.response.data.errors.phone_number
        })

      // this.$inertia.post('/web/profile/update-password',this.$data,{
      //   replace: true,
      //   preserveState: true,
      //   preserveScroll: true,
      // })
    }
  }
}
</script>

<style scoped>
</style>
