<template>
  <div class="contact-form">
    <div class="leave-comment">
      <!--                <p>Our staff will call back later and answer your questions.</p>-->
      <div action="#" class="comment-form">
        <div class="row">
          <div class="col-lg-6">
            <div class="">
              <input name="first_name" type="text" :placeholder="$page.props.$t.profile.first_name" v-model="user.first_name">
              <div class="p-2 text-red-500" v-if="$page.props.errors.first_name">{{ $page.props.errors.first_name }}</div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="">
              <input name="last_name" type="text" :placeholder="$page.props.$t.profile.last_name" v-model="user.last_name">
              <div class="p-2 text-red-500" v-if="$page.props.errors.last_name">{{ $page.props.errors.last_name }}</div>
            </div>
          </div>
          <div class="col-lg-12">
            <div class="">
              <input autocomplete="false" autofill="off" name="my-email-address-should-be-unique" type="email" :placeholder="$page.props.$t.profile.email_address" v-model="user.email_address"

              >
              <div class="p-2 text-red-500" v-if="$page.props.errors.email_address">{{ $page.props.errors.email_address }}</div>
            </div>
          </div>
          <div class="col-lg-12">
            <button type="submit"  class="site-btn" @click="saveData">{{ $page.props.$t.common.update }}</button>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UpdateInfoComponent',
  data () {
    return {
      user: this.$page.props.user
    }
  },
  methods: {
    saveData () {
      this.$inertia.patch('/web/profile/update-information', {
        first_name: this.user.first_name,
        last_name: this.user.last_name,
        email_address: this.user.email_address
      })
    }
  }
}
</script>

<style scoped>

</style>
