<template>
  <div class=" add-address">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="contact-form">
            <div class="leave-comment">
              <h4>{{ $page.props.$t.cart.create_shipping_address }}</h4>
              <div action="#" class="comment-form">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <input
                          v-model="first_name"
                          :placeholder="$page.props.$t.profile.first_name"
                          class="px-2"
                          type="text"
                      />
                      <div
                          v-if="$page.props.errors && $page.props.errors.first_name"
                          class="p-2 text-red-500"
                      >
                        {{ $page.props.errors.first_name }}
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <input
                          v-model="last_name"
                          :placeholder="$page.props.$t.profile.last_name"
                          class="px-2"
                          type="text"
                      />
                      <div
                          v-if="$page.props.errors && $page.props.errors.last_name"
                          class="p-2 text-red-500"
                      >
                        {{ $page.props.errors.last_name }}
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group page__dir-left">
                      <VuePhoneNumberInput
                          v-model="phone_number"
                          :no-country-selector="false"
                          :no-example="true"
                          :only-countries="['SA']"
                          :translations="{
                            countrySelectorLabel: $page.props.$t.profile.country,
                            countrySelectorError: 'Choisir un pays',
                            phoneNumberLabel: '5XXXXXXXXX',
                            example: 'ex: 5XXXXXXXXX',
                          }"
                          default-country-code="SA"
                      />

                      <!--                        <input type="text" placeholder="Phone Number" v-model="phone_number">-->
                      <div
                          v-if="$page.props.errors && $page.props.errors.phone_number"
                          class="p-2 text-red-500"
                      >
                        {{ $page.props.errors.phone_number }}
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <el-select v-model="city_id"

                                 :filterable="true"
                                 :placeholder="selectCityPlaceholder"
                                 class="page__w-full"
                                 no-data-text="No" no-match-text="No Data">
                        <template #empty>
                          <a
                              class="checkout__edit-icon"
                              href="/web/profile/create-shipping-address"
                              style="padding:5px"
                          >
                            <svg
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                              <defs/>
                              <path
                                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                              />
                            </svg>
                            {{ $page.props.$t.messages.select_city }}
                          </a>
                        </template>
                        <el-option
                            v-for="city in $page.props.cities"
                            :key="city.id"
                            :label="city.locale_name"
                            :value="city.id">
                          {{ city.locale_name }}
                        </el-option>
                      </el-select>
                      <div
                          v-if="$page.props.errors && $page.props.errors.city_id"
                          class="p-2 text-red-500"
                      >
                        {{ $page.props.errors.city_id }}
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <input
                          v-model="street_name"
                          :placeholder="$page.props.$t.profile.street_name"
                          class="px-2"
                          type="text"
                      />
                      <div
                          v-if="$page.props.errors && $page.props.errors.description"
                          class="p-2 text-red-500"
                      >
                        {{ $page.props.errors.description }}
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <input
                          v-model="area"
                          :placeholder="$page.props.$t.profile.area"
                          class="px-2"
                          type="text"
                      />
                      <div
                          v-if="$page.props.errors && $page.props.errors.description"
                          class="p-2 text-red-500"
                      >
                        {{ $page.props.errors.description }}
                      </div>

                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <input
                          v-model="description"
                          :placeholder="$page.props.$t.profile.address"
                          class="px-2"
                          type="text"
                      />
                      <div
                          v-if="$page.props.errors && $page.props.errors.description"
                          class="p-2 text-red-500"
                      >
                        {{ $page.props.errors.description }}
                      </div>

                    </div>
                    <button class="site-btn" type="submit" @click="saveData">
                      {{ $page.props.$t.common.save }}
                    </button>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
import VuePhoneNumberInput from 'vue-phone-number-input'
import 'vue-phone-number-input/dist/vue-phone-number-input.css'

export default {
  data () {
    return {
      first_name: this.$page.props.client.first_name,
      last_name: this.$page.props.client.last_name,
      phone_number: this.$page.props.client.phone_number,
      building_number: '',
      street_name: '',
      area: '',
      zip_code: '',
      description: '',
      city_id: null,
      cityObject: null
    }
  },
  name: 'CreateShippingAddressFormComponent',
  components: { VuePhoneNumberInput },
  props: {
    returnObject: {
      type: Boolean,
      default: false
    }
  },
  created() {
    const storedCityObject = localStorage.getItem('cart_shipping_city_id', null)
    if (storedCityObject) {
      this.cityObject = JSON.parse(storedCityObject)
      this.city_id = this.cityObject.id;
    }
  },
  computed: {
    selectCityPlaceholder() {
      if(this.cityId)
        return this.cityObject.locale_name
      return  this.$page.props.$t.messages.select_city;
    },
    getAddress () {
      return this.area + ' - ' + this.street_name + ' - ' + this.description
    }
  },
  methods: {
    saveData () {
      const data = this.$data
      data.description = this.getAddress
      data.return_object = this.returnObject
      this.$inertia.post('/web/profile/create-shipping-address', data)
      this.$inertia.on('invalid', (e) => {
        const statusCode = e.detail.response.status
        if (statusCode === 200 || statusCode === 201) {
          if (this.returnObject) {
            e.preventDefault()
            this.$emit('created', e.detail.response.data)
          }
        }
      })
    }
  }
}
</script>

<style>
.add-address .contact-form .leave-comment .comment-form input,
.add-address .contact-form .leave-comment .comment-form textarea {
  margin-bottom: 0px;
}

.add-address .contact-form .leave-comment .el-input__inner {
  margin-bottom: 0px;
  height: 50px !important;
}

</style>
