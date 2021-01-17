<template>
  <div>
 <!-- :default-index="clientList[0].id" -->
    <div class="row">
      <div class="col-md-6">
        <accounting-select-with-search-layout-component
           
            :no_all_option="true"
            :options="shippingMen"
            placeholder="مندوب التوصيل"
            title="مندوب التوصيل"
            identity="001"
            index="001"
            label_text="locale_name"
            @valueUpdated="deliveryManHasBeenChanged"
        >

        </accounting-select-with-search-layout-component>

      </div>
      <div class="col-md-6">
        <button class="btn btn-primary confirm" @click="confirm">تاكيد التسليم للمندوب
    </button>
      </div>
    </div> 

    

  </div>
</template>

<script>
export default {
  name: "OrderPaymentOptions",
  props: {
    order: {
      type: Object,
      required: true

    },
    shippingMen:{
      type:Array,
      required:true
    }
  },
  data(){
    return {
      deliveryManId:null
    }
  },
  methods: {
    deliveryManHasBeenChanged(e){
      this.deliveryManId = e.value ? e.value.id : null;
    },
    confirm() {
      this.$confirm('هل انت متاكد ؟', "تاكيد الحوالة", 'success').then(() => {
        axios.patch(`/api/orders/${this.order.id}/sign-to-delivery-man`).then(response => console.log(response.data));
      });
    },
   
  }
}
</script>
