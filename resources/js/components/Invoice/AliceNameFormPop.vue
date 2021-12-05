<template>
	<span>
    <button
      class="btn btn-custom-primary btn-sm"
      @click="show = true">
        اسم مستعار
    </button>
		<div v-if="show">
      <transition name="modal">
        <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button
                    class="pull-left btn btn-custom-primary"
                    type="button"
                    @click="show = false"
                  >
                    اغلاق
                  </button>
                  <h4 class="modal-title">الاسم المستعار</h4>
                </div>
                <div class="modal-body">
                  <input
                    @change="update"
                    v-model="name"
                    class="form-control"
                    type="text"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
	</span>
</template>

<script>
export default {
	name: "AliceNameFormPop",
  props: {
    invoiceId: {
      type: Number,
      default: () => null
    },
    aliceName: {
      type: String,
      default: () => null
    }
  },
  created() {
    this.name = this.aliceName;
  },
	data() {
		return {
			show: false,
			name: ""
		};
	},
  methods: {
    update() {
      if(this.invoiceId)
      {
        axios.patch(`/api/sales/${this.invoiceId}/alice_name`,{
          alice_name: this.name
        }).then(res => {
          location.reload()
        })
      }

      this.$emit('updated',{
        alice_name: this.name
      })


    }
  }
}
</script>

<style>

</style>
