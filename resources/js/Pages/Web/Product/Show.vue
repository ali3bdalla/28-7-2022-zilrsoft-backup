<template>
  <web-layout class="">
    <div class="container bg-white shadow-lg rounded-lg">
      <div class="my-10 flex gap-5">
        <div class="p-3 w-1/3 flex flex-col justify-between items-center">
          <div>
            <img
              class="object-contain"
              src="https://c1.neweggimages.com/ProductImage/34-155-519-V01.jpg"
            />
          </div>
          <div class="grid grid-cols-4 gap-2 mt-5 bg-gray-200 p-2">
            <div v-for="image in relatedImages" :key="image" class="">
              <img :src="image" class="w-full object-cover" />
            </div>
          </div>
        </div>
        <div
          class="p-3 text-center flex flex-col justify-center content-center mx-auto"
        >
          <h1 class="text-3xl uppercase tracking-wide font-bold">
            {{ $page.item.name }}
          </h1>
          <ProductRatingComponent
            align=""
            class="mt-2"
            :item="$page.item"
          ></ProductRatingComponent>
          <div class="pb-2 mb-3">
            <h4
              class="mt-2 text-2xl text-gray-900 font-bold inline-block line-through"
            >
              {{ parseFloat($page.item.price_with_tax).toFixed(2) }}
            </h4>
            <span class="text-sm text-web-primary font-bold">SR</span>
            <div>
              <h4
                class="mt-2 font-extrabold text-5xl text-gray-900 inline-block"
              >
                {{ parseFloat($page.item.price).toFixed(2) }}
              </h4>
              <span class="text-xl text-web-primary font-bold">SR</span>
            </div>
            <div class="mt-2 flex gap-3 items-center justify-center">
              <ToggleCartItemButtonComponent
                class="mt-2"
                :item="$page.item"
              ></ToggleCartItemButtonComponent>
              <ToggleFavoriteItemButtonComponent
                class="mt-2"
                :item="$page.item"
              ></ToggleFavoriteItemButtonComponent>
            </div>
          </div>
          <h2 class="text-lg font-bold text-left">Product Specification</h2>
          <div class="specification-table mt-2 pt-0 text-left w-full">
            <table class="w-full">
              <tbody>
                <tr
                  class="py-1"
                  v-for="filter in $page.item.filters"
                  :key="filter.id"
                >
                  <td
                    style="
                      border-color: #e7e7e7;
                      background-color: #f3f3f3;
                      color: #555;
                    "
                    class="bg-gray-100 py-1 px-3 font-bold text-left"
                  >
                    {{ filter.filter.name }}
                  </td>
                  <td class="text-left py-1 px-3 font-bold">
                    {{ filter.value.name }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="mt-10">
        <div
          class="grid p-2 grid-cols-2 md:grid-cols-4 gap-1 lg:gap-4 mb-5 mt-3"
        >
          <ProductListItemComponent
            v-for="(item, index) in $page.relatedItems"
            :key="item.id"
            :item="item"
            :index="index"
          ></ProductListItemComponent>
        </div>
      </div>
    </div>
  </web-layout>
</template>

<script>
import ProductListItemComponent from "./../../../components/Web/Product/ProductListItemComponent";
import ProductRatingComponent from "./../../../components/Web/Product/ProductRatingComponent";
import ToggleCartItemButtonComponent from "./../../../components/Web/Cart/ToggleCartItemButtonComponent";
import ToggleFavoriteItemButtonComponent from "./../../../components/Web/Cart/ToggleFavoriteItemButtonComponent";
import WebLayout from "../../../Layouts/WebAppLayout";
export default {
  components: {
    WebLayout,
    ProductListItemComponent,
    ProductRatingComponent,
    ToggleCartItemButtonComponent,
    ToggleFavoriteItemButtonComponent,
  },
  computed: {
    relatedImages() {
      return [
        "https://c1.neweggimages.com/ProductImageCompressAll1280/34-155-519-V80.jpg",
        "https://c1.neweggimages.com/ProductImageCompressAll1280/34-155-519-V12.jpg",
        "https://c1.neweggimages.com/ProductImageCompressAll1280/34-155-519-V06.jpg",
        "https://c1.neweggimages.com/ProductImageCompressAll1280/34-155-519-V07.jpg",
      ];
    },
  },
};
</script>

<style scoped>
.specification-table td {
  padding: 0px;
}
</style>