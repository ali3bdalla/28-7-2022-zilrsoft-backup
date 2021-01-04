<template>
  <div class="w-full md:w-48 relative">
    <button
    @click="toggleList"
      class="w-full h-full text-white flex justify-center gap-3 items-center py-1 text-white"
      style="background: rgb(87, 87, 87)"
    >
      <svg
        class="w-4 h-4"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"
        />
      </svg>

      الترتيب حسب
    </button>
 
    <div class="w-full absolute" v-if="isOpen">
      <div class="bg-white shadow-xl rounded-lg w-full">
        <ul class="divide-y divide-gray-300">
          <li class="p-2 hover:bg-gray-50 cursor-pointer" v-for="(item,index) in list" :key="index">
              {{item.title}}
          </li>
       
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    data(){
        return {
            list:[
                {
                    title:"السعر من الاقل الي الآعلى",
                    key:"price",
                    direction:"asc"
                },
                 {
                    title:"السعر من الآعلى الي الاقل",
                    key:"price",
                    direction:"desc"
                },

                 {
                    title:"الاحدث",
                    key:"id",
                    direction:"asc"
                },

                 {
                    title:"الاقدم",
                    key:"id",
                    direction:"desc"
                },
            ],
            isOpen:false
        }
    },
    methods:{
        toggleList()
        {
            this.isOpen = !this.isOpen;
        }
    }
};
</script>

<style>
</style>