<template>
  <div class="contact-form">
    <div class="leave-comment">
      <!--                <p>Our staff will call back later and answer your questions.</p>-->
      <div action="#" class="comment-form">
        <div class="row">
          <div class="col-lg-12">
            <div class="">
              <input type="password" placeholder="Old Password" v-model="old_password">
              <div class="p-2 text-red-500" v-if="$page.errors.old_password">{{ $page.errors.old_password }}</div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="">
              <input type="password" placeholder="New Password" v-model="password">
              <div class="p-2 text-red-500" v-if="$page.errors.password">{{ $page.errors.password }}</div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="">
              <input type="password"  placeholder="Password Confirmation" v-model="password_confirmation">
              <div class="p-2 text-red-500" v-if="$page.errors.password_confirmation">{{ $page.errors.password_confirmation }}</div>
            </div>
          </div>


          <div class="col-lg-12">
            <button type="submit" class="site-btn" @click="saveData">Save</button>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
name: "ChangePasswordComponent",
  data() {
    return {
      password:"",
      password_confirmation:"",
      old_password:""
    }
  },
  methods:{
    saveData() {
      this.$inertia.post('/web/profile/update-password',this.$data,{
        replace: true,
        preserveState: true,
        preserveScroll: true,
      })
    }
  }
}
</script>

<style scoped>

</style>