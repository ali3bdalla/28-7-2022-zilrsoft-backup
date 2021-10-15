<template>
  <web-layout>
    <div class="register-login-section">
      <div class="container">
        <div class="">
          <div class="col-lg-6 offset-lg-3">
            <div class="text-center login-form">
              <h2>{{ $page.props.$t.profile.sign_up }}</h2>
              <form @submit.prevent="form.post('/web/sign_up')">
                <div class="flex flex-col">
                  <div class="flex-1 group-input  page__dir-left">
                    <label>{{ $page.props.$t.profile.phone_number }}</label>
                    <InternationalPhoneNumberSelectorComponent
                        v-model="form.phone_number"></InternationalPhoneNumberSelectorComponent>
                    <div
                        v-if="form.errors.phone_number"
                        class="p-2 text-red-500"
                    >
                      {{ form.errors.phone_number }}
                    </div>
                  </div>
                  <div class="flex-1 group-input">
                    <label>{{ $page.props.$t.profile.password }}</label>
                    <input
                        v-model="form.password"
                        type="password"
                    />
                    <div v-if="form.errors.password" class="p-2 text-red-500">
                      {{ form.errors.password }}
                    </div>
                  </div>
                </div>

                <div class="flex">
                  <div class="flex-1 group-input">
                    <label>{{ $page.props.$t.profile.first_name }}</label>
                    <input
                        v-model="form.first_name"
                        type="text"/>
                    <div
                        v-if="form.errors.first_name"
                        class="p-2 text-red-500"
                    >
                      {{ form.errors.first_name }}
                    </div>
                  </div>
                  <div class="flex-1 group-input">
                    <label>{{ $page.props.$t.profile.last_name }}</label>
                    <input
                        v-model="form.last_name"
                        type="text"/>
                    <div v-if="form.errors.last_name" class="p-2 text-red-500">
                      {{ form.errors.last_name }}
                    </div>
                  </div>
                </div>
                <button class="site-btn login-btn" type="submit"  :disabled="form.processing">
                  {{ $page.props.$t.profile.sign_up }}
                </button>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
  </web-layout>
</template>

<script>
import WebLayout from '../../Layouts/WebAppLayout'
import InternationalPhoneNumberSelectorComponent
  from '../../Components/Utility/InternationalPhoneNumberSelectorComponent'

export default {
  name: 'Index',

  components: {
    InternationalPhoneNumberSelectorComponent,
    WebLayout
  },
  data () {
    return {
      form: this.$inertia.form({
        phone_number: '',
        password: '',
        first_name: '',
        last_name: ''
      }),
    }
  },
}
</script>
