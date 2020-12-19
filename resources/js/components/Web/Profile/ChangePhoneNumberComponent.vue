<template>
  <div class="contact-form">
    <div class="leave-comment">
      <!--                <p>Our staff will call back later and answer your questions.</p>-->
      <div action="#" class="comment-form">
        <div class="row">
          <div class="col-lg-12">
            <div class="">
              <input type="text" placeholder="First Name" v-model="$page.user.phone_number">
              <div class="p-2 text-red-500" v-if="$page.errors.phone_number">{{ $page.errors.phone_number }}</div>
            </div>
          </div>

          <div class="col-lg-12" v-if="optSent">
            <div class="">
              <input type="number" placeholder="Otp Code" v-model="otp">
              <div class="p-2 text-red-500" v-if="errors.otp">{{ errors.otp }}</div>
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
  name: "ChangePhoneNumberComponent",
  data() {
    return {
      optSent:false,
      otp:"",
      errors:[]
    }
  },
  methods:{
    saveData() {
      //
      // console.log('working')
      // axios.post('/web/profile/update-phone-number',{
      //   'otp' : this.otp,
      //     'phone_number' : this.$page.user.phone_number,
      // }).then(res => {
      //
      // }).catch(err => {
      //
      // });
      this.$inertia.post('/web/profile/update-phone-number',{
        'otp' : this.otp,
        'phone_number' : this.$page.user.phone_number,
      },{
        replace: true,
        preserveState: true,
        preserveScroll: true,
        only: [],
        headers: {},
        onCancelToken: cancelToken => {},
        onCancel: () => {},
        onBefore: visit => {},
        onStart: visit => {},
        onProgress: progress => {},
        onSuccess: page => {
          console.log(page.props.errors);
          if(!page.props.errors.phone_number)
          {
            this.optSent = true;
          }

        },
        onFinish: () => {},
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