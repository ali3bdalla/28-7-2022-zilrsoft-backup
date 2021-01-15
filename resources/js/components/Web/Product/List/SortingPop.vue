<template>
  <div class="product__search-sorting">
    <button
    @click="toggleList"
      class="product__search-option-button"
      style="background: rgb(87, 87, 87)"
    >
      <svg
        class="product__search-option-icon"
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
 
    <div class="product__search-sorting-panel" v-if="isOpen">
      <div class="product__search-sorting-panel-content">
        <ul class="product__search-sorting-list">
          <li @click="setSorting(item)" class="product__search-sorting-list-item" :class="{'product__search-sorting-list-item__active':activeSorting.title == item.title }" v-for="(item,index) in list" :key="index">
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
            activeSorting:{

            },
            list:[
                {
                    title:"السعر من الاقل الي الآعلى",
                    key:"online_offer_price",
                    direction:"asc"
                },
                 {
                    title:"السعر من الآعلى الي الاقل",
                    key:"online_offer_price",
                    direction:"desc"
                },

                 {
                    title:"الاحدث",
                    key:"id",
                    direction:"desc"
                },

                 {
                    title:"الاقدم",
                    key:"id",
                    direction:"asc"
                },
            ],
            isOpen:false
        }
    },
    methods:{
        setSorting(object)
        {
            if(object.title !== this.activeSorting.title)
            {
                this.activeSorting = object;
                this.$emit('updated',object)
                
            }
            
            this.toggleList();
        },
        toggleList()
        {
            this.isOpen = !this.isOpen;
        }
    }
};
</script>

<style>
</style>