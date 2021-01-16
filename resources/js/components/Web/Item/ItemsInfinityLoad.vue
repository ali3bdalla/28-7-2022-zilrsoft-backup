
<template>
  <div>
    <div class="products-grid">
      <ProductListItemComponent
        v-for="(item, index) in dataItems"
        :key="item.id"
        :item="item"
        :index="index"
      ></ProductListItemComponent>
    </div>
    <div class="page__mt-5">
      <infinite-loading @infinite="infiniteHandler"></infinite-loading>
    </div>
  </div>
</template>

<script>
import ProductListItemComponent from "../Product/ProductListItemComponent.vue";
export default {
  components: { ProductListItemComponent },
  props: {
    params: {
      type: Object,
      default: {},
    },
    // paramsUpdated:{
    //   type:function,
    //   handler(){
    //     console.log('call')
    //   }
    // }
  },
  data() {
    return {
      page: 1,
      dataItems: [],
    };
  },
  watch:{
      params:{
          deep:true,
          handler(value) {
              this.page = 1;
              this.dataItems = [];
              this.infiniteHandler();
          }
      }
  },
  methods: {
    
    paramsUpdated(){
      // console.log('working')
    },
    infiniteHandler($state) {
      let params = this.params;
      params.page = this.page;
      axios
        .get("/api/web/items/using_filters", {
          params: params,
        })
        .then(({ data }) => {
          if (data.data.length) {
            this.page += 1;
            this.dataItems.push(...data.data);
            $state.loaded();
          } else {
            $state.complete();
          }
        });
    },
  },
};
</script>