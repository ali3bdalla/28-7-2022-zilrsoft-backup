<template>
<div>
  <button @click="showConfirmationCode" :disabled="order.status != 'shipped'"
          style="background-color: transparent;border:none;color:black">
    <h2 :style="[order.status == 'shipped' ? 'color: blue;cursor: pointer':'color:white']" >#{{order.id}}</h2>

  </button>
</div>
</template>

<script>
export default {
name: "confirmOrderDeliveryStatus",
  props:{
    order:{
      type:Object,
      required:true
    },
    deliveryMan:{
      type:Object,
      required:true
    },
  },
  created() {

  },
  methods:{

    sendConfirmationCode() {

    },
    showConfirmationCode(){
      this.sendConfirmationCode();
      this.$prompt("Confirm Delivery Code","",this.order.id).then(text => {
        console.log(text)
        if(text !== null)
        {
          axios.post(`/delivery_man/confirm/${this.deliveryMan.hash}/${this.order.id}`,{
            code:text
          }).then(res => {
            console.log(res.data);
            this.$alert('order Delivered Successfully ','Success','success').then(res => {
              location.reload()
            })
          }).catch(error => {
            console.log(error.response);
            this.$alert('Wrong Code','Error','error').then(res => {
              this.showConfirmationCode()
            })
          })
        }
      });
    }
  }
}
</script>

<style scoped>

</style>