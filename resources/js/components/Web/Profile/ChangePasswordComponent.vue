<template>
  <div class="contact-form">
    <div class="leave-comment">
      <!--                <p>Our staff will call back later and answer your questions.</p>-->
      <div action="#" class="comment-form">
        <div class="row">
          <div class="col-lg-6">
            <div class="">
              <input type="text" placeholder="First Name" v-model="$page.user.first_name">
              <div class="p-2 text-red-500" v-if="$page.errors.first_name">{{ $page.errors.first_name }}</div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="">
              <input type="text" placeholder="Last Name" v-model="$page.user.last_name">
              <div class="p-2 text-red-500" v-if="$page.errors.last_name">{{ $page.errors.last_name }}</div>
            </div>
          </div>
          <div class="col-lg-12">
            <div class="">
              <input type="email" placeholder="E-mail Address" v-model="$page.user.email_address">
              <div class="p-2 text-red-500" v-if="$page.errors.last_name">{{ $page.errors.email_address }}</div>
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
name: "ChangePasswordComponent"
}
</script>

<style scoped>

</style>