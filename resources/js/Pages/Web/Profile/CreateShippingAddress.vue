<template>
  <web-layout>
    <section class="contact-section spad">
      <div class="container">
        <div class="row">
          <!--          <div class="col-lg-5">-->
          <!--            <div class="contact-title">-->
          <!--              <h4>Contacts Us</h4>-->
          <!--              <p>Contrary to popular belief, Lorem Ipsum is simply random text. It has roots in a piece of-->
          <!--                classical Latin literature from 45 BC, maki years old.</p>-->
          <!--            </div>-->
          <!--            <div class="contact-widget">-->
          <!--              <div class="cw-item">-->
          <!--                <div class="ci-icon">-->
          <!--                  <i class="ti-location-pin"></i>-->
          <!--                </div>-->
          <!--                <div class="ci-text">-->
          <!--                  <span>Address:</span>-->
          <!--                  <p>60-49 Road 11378 New York</p>-->
          <!--                </div>-->
          <!--              </div>-->
          <!--              <div class="cw-item">-->
          <!--                <div class="ci-icon">-->
          <!--                  <i class="ti-mobile"></i>-->
          <!--                </div>-->
          <!--                <div class="ci-text">-->
          <!--                  <span>Phone:</span>-->
          <!--                  <p>+65 11.188.888</p>-->
          <!--                </div>-->
          <!--              </div>-->
          <!--              <div class="cw-item">-->
          <!--                <div class="ci-icon">-->
          <!--                  <i class="ti-email"></i>-->
          <!--                </div>-->
          <!--                <div class="ci-text">-->
          <!--                  <span>Email:</span>-->
          <!--                  <p>hellocolorlib@gmail.com</p>-->
          <!--                </div>-->
          <!--              </div>-->
          <!--            </div>-->
          <!--          </div>-->
          <div class="col-lg-12 offset-lg-1">
            <div class="contact-form">
              <div class="leave-comment">
                <h4>Add Shipping Address</h4>
                <!--                <p>Our staff will call back later and answer your questions.</p>-->
                <div action="#" class="comment-form">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <input type="text" placeholder="First Name" v-model="first_name">
                        <div class="p-2 text-red-500" v-if="$page.errors.first_name">{{ $page.errors.first_name }}</div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <input type="text" placeholder="Last Name" v-model="last_name">
                        <div class="p-2 text-red-500" v-if="$page.errors.last_name">{{ $page.errors.last_name }}</div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <input type="text" placeholder="Phone Number" v-model="phone_number">
                        <div class="p-2 text-red-500" v-if="$page.errors.phone_number">{{ $page.errors.phone_number }}
                        </div>
                      </div>

                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <select v-model="city_id">
                          <option v-for="city in $page.cities" :key="city.id" :value="city.id">{{city.name}}</option>
                        </select>
                        <div class="p-2 text-red-500" v-if="$page.errors.city_id">{{ $page.errors.city_id }}</div>
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

                    <div class="col-lg-12">
                      <div class="form-group">
                        <textarea placeholder="Address" v-model="description"></textarea>
                        <div class="p-2 text-red-500" v-if="$page.errors.description">{{ $page.errors.description }}</div>
                      </div>
                      <button type="submit" class="site-btn" @click="saveData">Save</button>

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
import WebLayout from "../../../Layouts/WebAppLayout";

export default {
  components: {
    WebLayout,

  },
  data() {
    return {
      first_name: "",
      last_name: "",
      phone_number: "",
      building_number: "",
      street_name: "",
      zip_code: "",
      description: "",
      city_id: 0
    };
  },
  methods: {
    saveData() {
      this.$inertia.post('/web/profile/shipping_address', this.$data);
    }
  }


};
</script>

<style scoped>

select {
  width: 100%;
  font-size: 16px;
  color: #636363;
  height: 50px;
  border: 1px solid #ebebeb;
  border-radius: 5px;
  padding-left: 20px;

}

.contact-form .leave-comment .comment-form input ,.contact-form .leave-comment .comment-form textarea {
  margin-bottom: 0px;
}
</style>