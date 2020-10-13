<template>
  <div class="">
    <div class="font-sans mx-auto antialiased bg-grey-lightest">
      <!-- Top Nav -->
      <div class="w-full bg-green shadow-lg z-1000">
        <div class="container mx-auto">
          <div class="w-full flex justify-between items-center py-4 px-8">
            <!-- Brand -->
            <div class="text-center text-gray-400 font-bold">ZilrSoft</div>
            <!-- Navigation -->
            <div class="items-center hidden sm:flex">
              <a
                href="#"
                class="text-white hover:text-green-lightest no-underline mx-2 px-2 py-2"
                >Link 1</a
              >
              <a
                href="#"
                class="text-white hover:text-green-lightest no-underline mx-2 px-2 py-2"
                >Link 2</a
              >
              <a
                href="#"
                class="bg-green-dark hover:bg-green-darker rounded-full text-white no-underline mx-2 px-4 py-2"
                >Link 3</a
              >
            </div>
          </div>
        </div>
      </div>
      <!-- Content -->
      <div class="w-full bg-grey-lightest" style="padding-top: 1rem">
        <div class="container mx-auto py-8">
          <div class="w-5/6 mx-auto bg-gray-100 rounded shadow-lg">
            <div
              class="py-4 px-8 text-gray-500 text-2xl border-b border-grey-lighter"
            >
              Create Your Workspace
            </div>

            <div class="px-8">
              <div>
                <h1 class="text-3xl text-gray-500 mb-2">Organization</h1>
              </div>
              <div class="flex mb-4">
                <div class="w-1/2 mr-1">
                  <label class="block text-grey-darker text-sm font-bold mb-2"
                    >Title</label
                  >
                  <input
                    class="appearance-none border rounded w-full py-2 px-3 text-grey-darker"
                    type="text"
                    v-model="form.title"
                    placeholder="Your Organization Title"
                  />
                  <div class="p-2 text-red-500" v-if="$page.errors.title">
                    {{ $page.errors.title }}
                  </div>
                </div>
                <div class="w-1/2 ml-1">
                  <label
                    class="block text-grey-darker text-sm font-bold mb-2"
                    for="last_name"
                    >Business Type</label
                  >
                  <select
                    v-model="form.business_type_id"
                    class="appearance-none border rounded w-full py-2 px-3 text-grey-darker"
                  >
                    <option
                      :value="type.id"
                      v-for="type in $page.types"
                      :key="type.id"
                      v-text="type.name"
                    ></option>
                  </select>
                  <div
                    class="p-2 text-red-500"
                    v-if="$page.errors.business_type_id"
                  >
                    {{ $page.errors.business_type_id }}
                  </div>
                </div>
              </div>
              <div class="flex mb-4">
                <div class="w-1/2 ml-1">
                  <label
                    class="block text-grey-darker text-sm font-bold mb-2"
                    for="last_name"
                    >CR</label
                  >
                  <input
                    v-model="form.cr_number"
                    class="appearance-none border rounded w-full py-2 px-3 text-grey-darker"
                    type="text"
                    placeholder="CR ID Number"
                  />
                  <div class="p-2 text-red-500" v-if="$page.errors.cr_number">
                    {{ $page.errors.cr_number }}
                  </div>
                </div>

                <div class="w-1/2 mr-1">
                  <label
                    class="block text-grey-darker text-sm font-bold mb-2"
                    for="first_name"
                    >VAT-NUMBER</label
                  >
                  <input
                    v-model="form.vat_number"
                    class="appearance-none border rounded w-full py-2 px-3 text-grey-darker"
                    type="text"
                    placeholder="VAT ID Number"
                  />
                  <div class="p-2 text-red-500" v-if="$page.errors.vat_number">
                    {{ $page.errors.vat_number }}
                  </div>
                </div>
              </div>
              <div class="flex mb-4">
                <div class="w-1/2 mr-1">
                  <label
                    class="block text-grey-darker text-sm font-bold mb-2"
                    for="last_name"
                    >Country</label
                  >
                  <select
                    v-model="form.country_id"
                    class="appearance-none border rounded w-full py-2 px-3 text-grey-darker"
                  >
                    <option
                      :value="country.id"
                      v-for="country in $page.countries"
                      :key="country.id"
                      v-text="country.name"
                    ></option>
                  </select>
                  <div class="p-2 text-red-500" v-if="$page.errors.country_id">
                    {{ $page.errors.country_id }}
                  </div>
                </div>
                <div class="w-1/2 ml-1">
                  <label class="block text-grey-darker text-sm font-bold mb-2"
                    >City</label
                  >
                  <input
                    v-model="form.city"
                    class="appearance-none border rounded w-full py-2 px-3 text-grey-darker"
                    placeholder="City Name"
                  />
                  <div class="p-2 text-red-500" v-if="$page.errors.city">
                    {{ $page.errors.city }}
                  </div>
                </div>
              </div>

              <div class="flex mb-4">
                <div class="w-1/2 mr-1">
                  <label class="block text-grey-darker text-sm font-bold mb-2"
                    >Address</label
                  >
                  <textarea
                    v-model="form.address"
                    class="appearance-none border rounded w-full py-2 px-3 text-grey-darker"
                    placeholder="Address"
                  ></textarea>
                  <div class="p-2 text-red-500" v-if="$page.errors.address">
                    {{ $page.errors.address }}
                  </div>
                </div>
                <div class="w-1/2 ml-1">
                  <label class="block text-grey-darker text-sm font-bold mb-2"
                    >Description</label
                  >
                  <textarea
                    v-model="form.description"
                    class="appearance-none border rounded w-full py-2 px-3 text-grey-darker"
                    placeholder="Describe your organization"
                  ></textarea>
                  <div class="p-2 text-red-500" v-if="$page.errors.description">
                    {{ $page.errors.description }}
                  </div>
                </div>
              </div>

              <div class="flex mb-4">
                <div class="w-1/2 mr-1">
                  <label class="block text-grey-darker text-sm font-bold mb-2"
                    >Logo</label
                  >
                  <input
                    @change="logoUpdated"
                    ref="file"
                    class="appearance-none border rounded w-full py-2 px-3 text-grey-darker"
                    accept="image/*"
                    type="file"
                  />
                  <div class="p-2 text-red-500" v-if="$page.errors.logo">
                    {{ $page.errors.logo }}
                  </div>
                </div>

                <div class="w-1/2 mr-1">
                  <label class="block text-grey-darker text-sm font-bold mb-2"
                    >Stamp</label
                  >
                  <input
                    @change="stampUpdated"
                    ref="file"
                    class="appearance-none border rounded w-full py-2 px-3 text-grey-darker"
                    accept="image/*"
                    type="file"
                  />
                  <div class="p-2 text-red-500" v-if="$page.errors.stamp">
                    {{ $page.errors.stamp }}
                  </div>
                </div>
              </div>
            </div>
            <div class="pb-5 px-8">
              <div><h1 class="text-3xl text-gray-500 mb-2">Supervisor</h1></div>
              <div class="flex mb-4">
                <div class="w-1/2 mr-1">
                  <label class="block text-grey-darker text-sm font-bold mb-2"
                    >Name</label
                  >
                  <input
                    class="appearance-none border rounded w-full py-2 px-3 text-grey-darker"
                    v-model="form.name"
                    type="text"
                    placeholder="Your Name"
                  />
                  <div class="p-2 text-red-500" v-if="$page.errors.name">
                    {{ $page.errors.name }}
                  </div>
                </div>
                <div class="w-1/2 ml-1">
                  <label class="block text-grey-darker text-sm font-bold mb-2"
                    >E-mail Address</label
                  >
                  <input
                    class="appearance-none border rounded w-full py-2 px-3 text-grey-darker"
                    v-model="form.email"
                    type="text"
                    placeholder="Your Email Address"
                  />
                  <div class="p-2 text-red-500" v-if="$page.errors.email">
                    {{ $page.errors.email }}
                  </div>
                </div>
              </div>
              <div class="flex mb-4">
                <div class="w-1/2 mr-1">
                  <label class="block text-grey-darker text-sm font-bold mb-2"
                    >Password</label
                  >
                  <input
                    class="appearance-none border rounded w-full py-2 px-3 text-grey-darker"
                    v-model="form.password"
                    type="password"
                    placeholder="Password"
                  />
                  <div class="p-2 text-red-500" v-if="$page.errors.password">
                    {{ $page.errors.password }}
                  </div>
                </div>
                <div class="w-1/2 ml-1">
                  <label class="block text-grey-darker text-sm font-bold mb-2"
                    >Password Confirmation</label
                  >
                  <input
                    class="appearance-none border rounded w-full py-2 px-3 text-grey-darker"
                    v-model="form.password_confirmation"
                    type="password"
                    placeholder="Password Confirmation"
                  />
                  <div
                    class="p-2 text-red-500"
                    v-if="$page.errors.password_confirmation"
                  >
                    {{ $page.errors.password_confirmation }}
                  </div>
                </div>
              </div>
              <div class="flex mb-4">
                <div class="w-full mr-1">
                  <label
                    class="block text-grey-darker text-sm font-bold mb-2"
                    for="first_name"
                    >Phone number</label
                  >
                  <input
                    class="appearance-none border rounded w-full py-2 px-3 text-grey-darker"
                    v-model="form.phone_number"
                    type="text"
                    placeholder="Your Phone Number"
                  />
                  <div
                    class="p-2 text-red-500"
                    v-if="$page.errors.phone_number"
                  >
                    {{ $page.errors.phone_number }}
                  </div>
                </div>
              </div>
              <div class="flex items-center justify-between mt-8">
                <button
                  @click="submitRegister"
                  :disabled="inValidData"
                  class="bg-blue-400 hover:bg-blue-dark text-white font-bold py-2 px-4 rounded-full"
                  type="submit"
                >
                  Sign Up
                </button>
              </div>
            </div>
          </div>
          <p class="text-center my-4">
            <inertia-link
              href="/login"
              class="text-grey-dark text-sm no-underline hover:text-grey-darker"
              >I already have an account</inertia-link
            >
          </p>
        </div>
      </div>
      <!-- Footer -->
      <footer class="w-full bg-grey-lighter py-8">
        <div class="container mx-auto text-center px-8">
          <p class="text-grey-dark mb-2 text-sm">
            <span class="font-bold">ZilrSoft &copy; 2020</span>
          </p>
        </div>
      </footer>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      inValidData: true,
      form: {
        logo: null,
        stamp: null,
        title: "",
        country_id: 1,
        vat_number: "",
        city: "",
        cr_number: "",
        business_type_id: "",
        address: "",
        description: "",
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        phone_number: "",
      },
    };
  },
  methods: {
    submitRegister() {
      let formData = new FormData();
      formData.append("category_id", this.form.category_id);
      formData.append("description", this.form.description);
      formData.append("duration", this.form.duration);
      
      this.$inertia.post("/register", this.form);
    },
    logoUpdated(e) {
      this.form.logo = e.target.files[0];
      // for (let i = 0; i <= images.length; i++) {
      //   let imageFile = images[i];
      //   if (imageFile != null) {
      //     if (imageFile.type.indexOf("image") === -1) {
      //       alert("This is not an image file");
      //       console.log("This is not an image file");
      //     } else {
      //       this.form.images.push(imageFile);
      //     }
      //   }
      // }
    },
    stampUpdated(e) {
      this.form.stamp = e.target.files[0];
      // let images = e.target.files;
      // for (let i = 0; i <= images.length; i++) {
      //   let imageFile = images[i];
      //   if (imageFile != null) {
      //     if (imageFile.type.indexOf("image") === -1) {
      //       alert("This is not an image file");
      //       console.log("This is not an image file");
      //     } else {
      //       this.form.images.push(imageFile);
      //     }
      //   }
      // }
    },
  },
};
</script>

<style></style>
