<template>
  <images-upload-layout>
    <div class="register-login-section spad">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 offset-lg-3">
            <div class="login-form">
              <h2>Enter Password</h2>

              <form action="#">
                <div class="flex flex-col">

                  <div class="flex-1 group-input">
                    <label for="password">Password</label>
                    <input
                        id="password"
                        v-model="password"
                        auto-complete="new_zilrsoft_password"
                        autocomplete="new_zilrsoft_password"
                        placeholder="Password"
                        type="password"
                    />
                    <div v-if="$page.errors.password" class="p-2 text-red-500">
                      {{ $page.errors.password }}
                    </div>
                  </div>
                </div>

              </form>

              <button
                  class="site-btn login-btn"
                  type="button"
                  @click="submitForm"
              >
                Password
              </button>


            </div>
          </div>
        </div>
      </div>
    </div>
  </images-upload-layout>
</template>

<script>
import ImagesUploadLayout from "../../Layouts/ImagesUploadLayout";

export default {
  name: "Index",
  components: {ImagesUploadLayout},
  data() {
    return {
      password: "",
    };
  },

  methods: {
    submitForm() {
      this.$inertia.post("/images_upload/auth", {
        password: this.password,
      });
    },
  },
};
</script>

<style scoped>
</style>