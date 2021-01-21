<template>
  <web-layout>
    <section class="contact-section spad">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="contact-form">
              <div class="leave-comment">
                <h4>{{ $page.$t.cart.create_shipping_address }}</h4>
                <!--                <p>Our staff will call back later and answer your questions.</p>-->
                <div action="#" class="comment-form">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <input
                          type="text"
                          class="px-2"
                          :placeholder="$page.$t.profile.first_name"
                          v-model="first_name"
                        />
                        <div
                          class="p-2 text-red-500"
                          v-if="$page.errors.first_name"
                        >
                          {{ $page.errors.first_name }}
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <input
                          type="text"
                          class="px-2"
                          :placeholder="$page.$t.profile.last_name"
                          v-model="last_name"
                        />
                        <div
                          class="p-2 text-red-500"
                          v-if="$page.errors.last_name"
                        >
                          {{ $page.errors.last_name }}
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
                            countrySelectorLabel: $page.$t.profile.country,
                            countrySelectorError: 'Choisir un pays',
                            phoneNumberLabel: '5XXXXXXXXX',
                            example: 'ex: 5XXXXXXXXX',
                          }"
                          default-country-code="SA"
                        />

                        <!--                        <input type="text" placeholder="Phone Number" v-model="phone_number">-->
                        <div
                          class="p-2 text-red-500"
                          v-if="$page.errors.phone_number"
                        >
                          {{ $page.errors.phone_number }}
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                         <el-select  no-match-text="No Data" no-data-text="No" :filterable="true"  v-model="city_id" :placeholder=" $page.$t.messages.select_city" class="page__w-full" >
            <!-- <template #prefix>Click Me</template> -->
            <template #empty>
               <a
               style="padding:5px"
              class="checkout__edit-icon"
              href="/web/profile/create-shipping-address"
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
           {{ $page.$t.messages.select_city}}
          </a>
            </template>
            <el-option
                v-for="city in $page.cities"
                :key="city.id"
                :label="city.name"
                :value="city.id">
              {{ city.name }}
            </el-option>
          </el-select>
                        <!-- <select v-model="city_id">
                          <option value="0">
                            {{ $page.$t.messages.select_city }}
                          </option>
                          <option
                            v-for="city in $page.cities"
                            :key="city.id"
                            :value="city.id"
                          >
                            {{ city.name }}
                          </option>
                        </select> -->
                        <div
                          class="p-2 text-red-500"
                          v-if="$page.errors.city_id"
                        >
                          {{ $page.errors.city_id }}
                        </div>
                      </div>
                    </div>
                    <!--                    <div class="col-lg-6">-->
                    <!--                      <div class="form-group">-->
                    <!--                        <input type="text" placeholder="Building Number" v-model="building_number">-->
                    <!--                        <div class="p-2 text-red-500" v-if="$page.errors.building_number">{{-->
                    <!--                          $page.errors.building_number }}-->
                    <!--                        </div>-->
                    <!--                      </div>-->
                    <!--                    </div>-->
                    <!--                    <div class="col-lg-6">-->
                    <!--                      <div class="form-group">-->
                    <!--                        <input type="text" placeholder="Street Name" v-model="street_name">-->
                    <!--                        <div class="p-2 text-red-500" v-if="$page.errors.street_name">{{ $page.errors.street_name }}-->
                    <!--                        </div>-->
                    <!--                      </div>-->
                    <!--                    </div>-->
                    <!--                    <div class="col-lg-6">-->
                    <!--                      <div class="form-group">-->
                    <!--                        <input type="text" placeholder="Zip Code" v-model="zip_code">-->
                    <!--                        <div class="p-2 text-red-500" v-if="$page.errors.zip_code">{{ $page.errors.zip_code }}</div>-->
                    <!--                      </div>-->
                    <!--                    </div>-->

                    <div class="col-lg-6">
                      <div class="form-group">
                        <input
                          type="text"
                          class="px-2"
                          :placeholder="$page.$t.profile.street_name"
                          v-model="street_name"
                        />
                        <div
                          class="p-2 text-red-500"
                          v-if="$page.errors.description"
                        >
                          {{ $page.errors.description }}
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <input
                          type="text"
                          class="px-2"
                          :placeholder="$page.$t.profile.area"
                          v-model="area"
                        />
                        <div
                          class="p-2 text-red-500"
                          v-if="$page.errors.description"
                        >
                          {{ $page.errors.description }}
                        </div>

                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="form-group">
                        <input
                          type="text"
                          class="px-2"
                          :placeholder="$page.$t.profile.address"
                          v-model="description"
                        />
                        <div
                          class="p-2 text-red-500"
                          v-if="$page.errors.description"
                        >
                          {{ $page.errors.description }}
                        </div>

                      </div>
                      <button type="submit" class="site-btn" @click="saveData">
                        {{ $page.$t.common.save }}
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
    </section>
  </web-layout>
</template>

<script>
import WebLayout from '../../../Layouts/WebAppLayout'

import VuePhoneNumberInput from 'vue-phone-number-input'
import 'vue-phone-number-input/dist/vue-phone-number-input.css'

export default {
  components: {
    WebLayout,
    VuePhoneNumberInput
  },
  data () {
    return {
      first_name: '',
      last_name: '',
      phone_number: '',
      building_number: '',
      street_name: '',
      area: '',
      zip_code: '',
      description: '',
      city_id: ''
    }
  },
  computed: {
    getAddress () {
      return this.area + ' - ' + this.street_name + ' - ' + this.description
    }
  },
  methods: {
    saveData () {
      const data = this.$data
      data.description = this.getAddress
      this.$inertia.post('/web/profile/create-shipping-address', data)
    }
  }
}
</script>

<style scoped>
/* select {
  width: 100%;
  font-size: 16px;
  color: #636363;
  height: 50px;
  border: 1px solid #ebebeb;
  border-radius: 5px;
  padding-left: 20px;
} */

.contact-form .leave-comment .comment-form input,
.contact-form .leave-comment .comment-form textarea {
  margin-bottom: 0px;
}

</style>
