<template>
  <web-layout>
    <!-- <SideBarComponent :items='$page.items'></SideBarComponent> -->
    <div class="container">
      <div
        class="grid w-full grid-cols-1 gap-3 my-5 bg-white shadow-xl md:grid-cols-2"
      >
        <a
          class="h-48 md:h-64 text-"
          :href="`/web/categories/${category.id}`"
          v-for="(category, index) in mainCategories"
          :key="index"
        >
          <div class="flex items-center justify-center">
            <div class="flex-1 ">

              <div class="flex items-center justify-center w-full h-48 min-h-full p-3 text-xl font-bold text-center text-white md:text-2xl md:h-64 bg-web-primary">

              {{ category.locale_name }}
              </div>
            </div>
            <div class="flex-1">
              <img
              class="object-cover w-full h-48 border md:h-64"
                src="data:image/jpeg;base64,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"
              />

              <!-- <img src="https://media.extra.com/i/aurora/Mobiles_1x4_en.webp" class="object-cover w-full h-48 md:h-64" />
              <div>
                {{category.locale_name}}
              </div> -->
            </div>
          </div>
        </a>
      </div>
    </div>
  </web-layout>
</template>

<script>
import SideBarComponent from "./../../../components/Web/Page/SideBarComponent";
import WebLayout from "../../../Layouts/WebAppLayout";

export default {
  name: "Index",
  components: {
    WebLayout,
    SideBarComponent,
  },
  data() {
    return {
      images: [
        "https://promotions.newegg.com/international/20-2402/308x627@2x.jpg",
        "https://promotions.newegg.com/international/20-2402/308x308left@2x.jpg",
        "https://promotions.newegg.com/international/20-2402/308x308right@2x.jpg",
        "https://promotions.newegg.com/international/20-2402/627x308@2x.jpg",
      ],
    };
  },
  computed: {
    mainCategories() {
      let categories = [];
      for (const index in this.$page.categories) {
        let category = this.$page.categories[index];
        category.image = this.images[index];

        categories.push(category);
      }
      //  this.$page.categories.forEach((category) => {

      // });

      return categories;
    },
  },
};
</script>

<style scoped>
</style>