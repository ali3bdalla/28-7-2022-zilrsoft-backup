<template>
  <div>
    <!-- <SideBarComponent :items='$page.items'></SideBarComponent> -->
    <div class="container  my-5 grid grid-cols-3 gap-3">
      <a
      class=""
       :href="`/web/categories/${category.id}`"
        :class="[
          index == 0
            ? 'row-span-2'
            : index == 1
            ? ''
            : index == 2
            ? ''
            : 'col-span-2',
        ]"
        v-for="(category, index) in mainCategories"
        :key="index"
      >
        <div>
          <img :src="category.image" />
        </div>
      </a>
    </div>
  </div>
</template>

<script>
import SideBarComponent from "./../../../components/Web/Page/SideBarComponent";

export default {
  name: "Index",
  components: {
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