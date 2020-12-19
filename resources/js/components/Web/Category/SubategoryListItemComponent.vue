<template>
  <div
      class=" p-3 bg-white border-l border-b border-r transition hover:shadow-md  relative"
  >
    <div
        class="text-sm bg-web-primary absolute   shadow-xl rounded font-extrabold truncate inline-block py-1 px-2 text-white  text-center"
    >
      {{ category.products_count }} {{$page.$t.products.product}}
    </div>
    <div class="overflow-hidden w-full">
      <img
          src="https://scontent.fruh4-5.fna.fbcdn.net/v/t1.0-9/48397569_2369898056390725_8150239488325451776_o.jpg?_nc_cat=108&ccb=2&_nc_sid=730e14&_nc_ohc=-PoMUrZfEm8AX-aeG1r&_nc_ht=scontent.fruh4-5.fna&oh=34eea4d221547c586334b4f938c3a4f4&oe=6003246B"
          class="object-cover h-84 w-full"/>
    </div>
    <h3 class="mt-4 text-sm bg-gray-200  text-gray-900 shadow-sm rounded font-extrabold truncate inline-block py-1 px-2  w-full text-center ">
      {{ category.locale_name }}
    </h3>
    <!-- <div class="mt-5 h-16 overflow-hidden">
      <h3 class="text-xl font-extrabold">{{ category.name }}</h3>
      <p class="mt-2 text-sm truncate">{{ category.description }}</p>
    </div> -->
  </div>
</template>

<script>
export default {
  props: ["category"],

};

</script>

<style>
</style>>