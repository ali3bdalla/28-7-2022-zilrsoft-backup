<template>
  <div class="grid grid-cols-10 gap-3" style="padding: 10px">
    <div v-for="image in images" :key="image">
      <img class="w-full h-20 object-cover" :src="image" />
    </div>
  </div>
</template>

<script>
export default {
  name: "Index",
  computed:{
    images()
    {
      return [
          "https://images.unsplash.com/photo-1603292443162-bc1f3b63e14a?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1234&q=80",
"https://images.unsplash.com/photo-1603214767301-4279749923e8?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1234&q=80",
          "https://images.unsplash.com/photo-1603291999682-8c0522eed67c?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1234&q=80",
          "https://images.unsplash.com/photo-1603264285193-e69d41b6c4ea?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1234&q=80",
          "https://images.unsplash.com/photo-1603270872419-c53c6d705028?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2700&q=80",
          "https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2000&q=80",
          "https://images.unsplash.com/photo-1556656793-08538906a9f8?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2700&q=80",
          "https://images.unsplash.com/photo-1535303311164-664fc9ec6532?ixlib=rb-1.2.1&auto=format&fit=crop&w=1234&q=80"
      ];
    }

  }
}
</script>

<style scoped>

</style>